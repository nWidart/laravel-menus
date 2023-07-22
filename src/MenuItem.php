<?php

namespace Nwidart\Menus;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Nwidart\Menus\Traits\CanHide;
use Collective\Html\HtmlFacade as HTML;
use Illuminate\Support\Facades\Request;
use Illuminate\Contracts\Support\Arrayable as ArrayableContract;

/**
 * @property string url
 * @property string route
 * @property string title
 * @property string name
 * @property string icon
 * @property int parent
 * @property array attributes
 * @property bool active
 * @property int order
 */
class MenuItem implements ArrayableContract
{
    use CanHide;
    /**
     * Array properties.
     *
     * @var array
     */
    protected $properties = [];

    /**
     * The child collections for current menu item.
     *
     * @var array
     */
    protected $childs = array();

    /**
     * The fillable attribute.
     *
     * @var array
     */
    protected $fillable = array(
        'url',
        'route',
        'title',
        'name',
        'icon',
        'parent',
        'attributes',
        'active',
        'order',
        'hideWhen',
    );

    /**
     * Constructor.
     *
     * @param array $properties
     */
    public function __construct($properties = array())
    {
        $this->properties = $properties;
        $this->fill($properties);
    }

    /**
     * Set the icon property when the icon is defined in the link attributes.
     *
     * @param array $properties
     *
     * @return array
     */
    protected static function setIconAttribute(array $properties)
    {
        $icon = Arr::get($properties, 'attributes.icon');
        if (!is_null($icon)) {
            $properties['icon'] = $icon;

            Arr::forget($properties, 'attributes.icon');

            return $properties;
        }

        return $properties;
    }

    /**
     * Get random name.
     *
     * @param array $attributes
     *
     * @return string
     */
    protected static function getRandomName(array $attributes)
    {
        return substr(md5(Arr::get($attributes, 'title', Str::random(6))), 0, 5);
    }

    /**
     * Create new static instance.
     *
     * @param array $properties
     *
     * @return static
     */
    public static function make(array $properties)
    {
        $properties = self::setIconAttribute($properties);

        return new static($properties);
    }

    /**
     * Fill the attributes.
     *
     * @param array $attributes
     */
    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Create new menu child item using array.
     *
     * @param $attributes
     *
     * @return $this
     */
    public function child($attributes)
    {
        $this->childs[] = static::make($attributes);

        return $this;
    }

    /**
     * Register new child menu with dropdown.
     *
     * @param $title
     * @param callable $callback
     *
     * @return $this
     */
    public function dropdown($title, \Closure $callback, $order = 0, array $attributes = array())
    {
        $properties = compact('title', 'order', 'attributes');

        if (func_num_args() === 3) {
            $arguments = func_get_args();

            $title = Arr::get($arguments, 0);
            $attributes = Arr::get($arguments, 2);

            $properties = compact('title', 'attributes');
        }

        $child = static::make($properties);

        call_user_func($callback, $child);

        $this->childs[] = $child;

        return $child;
    }

    /**
     * Create new menu item and set the action to route.
     *
     * @param $route
     * @param $title
     * @param array $parameters
     * @param array $attributes
     *
     * @return MenuItem
     */
    public function route($route, $title, $parameters = array(), $order = 0, $attributes = array())
    {
        if (func_num_args() === 4) {
            $arguments = func_get_args();

            return $this->add([
                'route' => [Arr::get($arguments, 0), Arr::get($arguments, 2)],
                'title' => Arr::get($arguments, 1),
                'attributes' => Arr::get($arguments, 3),
            ]);
        }

        $route = array($route, $parameters);

        return $this->add(compact('route', 'title', 'order', 'attributes'));
    }

    /**
     * Create new menu item  and set the action to url.
     *
     * @param $url
     * @param $title
     * @param array $attributes
     *
     * @return MenuItem
     */
    public function url($url, $title, $order = 0, $attributes = array())
    {
        if (func_num_args() === 3) {
            $arguments = func_get_args();

            return $this->add([
                'url' => Arr::get($arguments, 0),
                'title' => Arr::get($arguments, 1),
                'attributes' => Arr::get($arguments, 2),
            ]);
        }

        return $this->add(compact('url', 'title', 'order', 'attributes'));
    }

    /**
     * Add new child item.
     *
     * @param array $properties
     *
     * @return $this
     */
    public function add(array $properties)
    {
        $item = static::make($properties);

        $this->childs[] = $item;

        return $item;
    }

    /**
     * Add new divider.
     *
     * @param int $order
     *
     * @return self
     */
    public function addDivider($order = null)
    {
        $item = static::make(array('name' => 'divider', 'order' => $order));

        $this->childs[] = $item;

        return $item;
    }

    /**
     * Alias method instead "addDivider".
     *
     * @param int $order
     *
     * @return MenuItem
     */
    public function divider($order = null)
    {
        return $this->addDivider($order);
    }

    /**
     * Add dropdown header.
     *
     * @param $title
     *
     * @return $this
     */
    public function addHeader($title)
    {
        $item = static::make(array(
            'name' => 'header',
            'title' => $title,
        ));

        $this->childs[] = $item;

        return $item;
    }

    /**
     * Same with "addHeader" method.
     *
     * @param $title
     *
     * @return $this
     */
    public function header($title)
    {
        return $this->addHeader($title);
    }

    /**
     * Get childs.
     *
     * @return array
     */
    public function getChilds()
    {
        if (config('menus.ordering')) {
            return collect($this->childs)->sortBy('order')->all();
        }

        return $this->childs;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        if ($this->route !== null) {
            return route($this->route[0], $this->route[1]);
        }

        if (empty($this->url)) {
            return url('/#');
        }

        return url($this->url);
    }

    /**
     * Get request url.
     *
     * @return string
     */
    public function getRequest()
    {
        return ltrim(str_replace(url('/'), '', $this->getUrl()), '/');
    }

    /**
     * Get icon.
     *
     * @param null|string $default
     *
     * @return string
     */
    public function getIcon($default = null)
    {
        if ($this->icon !== null && $this->icon !== '') {
            return '<i class="' . $this->icon . '"></i>';
        }
        if ($default === null) {
            return $default;
        }

        return '<i class="' . $default . '"></i>';
    }

    /**
     * Get properties.
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Get HTML attribute data.
     *
     * @return mixed
     */
    public function getAttributes()
    {
        $attributes = $this->attributes ? $this->attributes : [];

        Arr::forget($attributes, ['active', 'icon']);

        return HTML::attributes($attributes);
    }

    /**
     * Check is the current item divider.
     *
     * @return bool
     */
    public function isDivider()
    {
        return $this->is('divider');
    }

    /**
     * Check is the current item divider.
     *
     * @return bool
     */
    public function isHeader()
    {
        return $this->is('header');
    }

    /**
     * Check is the current item divider.
     *
     * @param $name
     *
     * @return bool
     */
    public function is($name)
    {
        return $this->name == $name;
    }

    /**
     * Check is the current item has sub menu .
     *
     * @return bool
     */
    public function hasSubMenu()
    {
        return !empty($this->childs);
    }

    /**
     * Same with hasSubMenu.
     *
     * @return bool
     */
    public function hasChilds()
    {
        return $this->hasSubMenu();
    }

    /**
     * Check the active state for current menu.
     *
     * @return mixed
     */
    public function hasActiveOnChild()
    {
        if ($this->inactive()) {
            return false;
        }

        return $this->hasChilds() ? $this->getActiveStateFromChilds() : false;
    }

    /**
     * Get active state from child menu items.
     *
     * @return bool
     */
    public function getActiveStateFromChilds()
    {
        foreach ($this->getChilds() as $child) {
            if ($child->inactive()) {
                continue;
            }
            if ($child->hasChilds()) {
                if ($child->getActiveStateFromChilds()) {
                    return true;
                }
            } elseif ($child->isActive()) {
                return true;
            } elseif ($child->hasRoute() && $child->getActiveStateFromRoute()) {
                return true;
            } elseif ($child->getActiveStateFromUrl()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get inactive state.
     *
     * @return bool
     */
    public function inactive()
    {
        $inactive = $this->getInactiveAttribute();

        if (is_bool($inactive)) {
            return $inactive;
        }

        if ($inactive instanceof \Closure) {
            return call_user_func($inactive);
        }

        return false;
    }

    /**
     * Get active attribute.
     *
     * @return string
     */
    public function getActiveAttribute()
    {
        return Arr::get($this->attributes, 'active');
    }

    /**
     * Get inactive attribute.
     *
     * @return string
     */
    public function getInactiveAttribute()
    {
        return Arr::get($this->attributes, 'inactive');
    }

    /**
     * Get active state for current item.
     *
     * @return mixed
     */
    public function isActive()
    {
        if ($this->inactive()) {
            return false;
        }

        $active = $this->getActiveAttribute();

        if (is_bool($active)) {
            return $active;
        }

        if ($active instanceof \Closure) {
            return call_user_func($active);
        }

        if ($this->hasRoute()) {
            return $this->getActiveStateFromRoute();
        }

        return $this->getActiveStateFromUrl();
    }

    /**
     * Determine the current item using route.
     *
     * @return bool
     */
    protected function hasRoute()
    {
        return !empty($this->route);
    }

    /**
     * Get active status using route.
     *
     * @return bool
     */
    protected function getActiveStateFromRoute()
    {
        return Request::is(str_replace(url('/') . '/', '', $this->getUrl()));
    }

    /**
     * Get active status using request url.
     *
     * @return bool
     */
    protected function getActiveStateFromUrl()
    {
        return Request::is($this->url);
    }

    /**
     * Set order value.
     *
     * @param  int $order
     * @return self
     */
    public function order($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getProperties();
    }

    /**
     * Get property.
     *
     * @param string $key
     *
     * @return string|null
     */
    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : null;
    }
}
