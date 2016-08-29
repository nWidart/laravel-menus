[![Latest Version on Packagist](https://img.shields.io/packagist/v/nwidart/laravel-menus.svg?style=flat-square)](https://packagist.org/packages/nwidart/laravel-menus)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/nWidart/laravel-menus/master.svg?style=flat-square)](https://travis-ci.org/nWidart/laravel-menus)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/nWidart/laravel-menus.svg?style=flat-square)](https://scrutinizer-ci.com/g/nWidart/laravel-menus/?branch=master)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/6b187410-e586-465f-a137-2d1fbf7ac724.svg?style=flat-square)](https://insight.sensiolabs.com/projects/6b187410-e586-465f-a137-2d1fbf7ac724)
[![Quality Score](https://img.shields.io/scrutinizer/g/nWidart/laravel-menus.svg?style=flat-square)](https://scrutinizer-ci.com/g/nWidart/laravel-menus)
[![Total Downloads](https://img.shields.io/packagist/dt/nwidart/laravel-menus.svg?style=flat-square)](https://packagist.org/packages/nwidart/laravel-menus)



# Laravel Menus

`nwidart/laravel-menus` is a laravel package which created to manage menus. It has a feature called presenters which enables easy styling and custom structure of menu rendering.

This package is a re-published, re-organised and maintained version of [pingpong/menus](https://github.com/pingpong-labs/menus), which isn't maintained anymore. This package is used in [AsgardCMS](https://asgardcms.com/).

With one big added bonus that the original package didn't have: **tests**.

- [Installation](#installation)
- [Creating A Menu](#creating-a-menu)
  - [Menu Item](#menu-item)
  - [Menu Dropdown](#menu-dropdown)
  - [Menu Dropdown Milti Level](#menu-dropdown-multi-level)
  - [Menu Divider](#menu-divider)
  - [Dropdown Header](#dropdown-header)
  - [Ordering Menu Item](#ordering-menu-item)
  - [Make Lots of Menu](#make-lots-of-menu)
- [Menu Presenter](#menu-presenter)
  - [The Available Presenter](#available-presenter)
  - [Make A Custom Presenter](#make-a-custom-presenter)
  - [Register A New Menu Style](#register-a-menu-style)
- [View Presenter](#view-presenter)
  - [The Available View Presenter](#available-view-presenter)
- [Rendering Menu](#rendering-menu)
- [Menu Inheritance](#menu-inheritance)
  - [Menu Instance](#menu-instance)
  - [Finding Menu Item](#finding-menu-item)
  - [Modifying Menu](#modifying-menu)


## Installation

You can install the through composer command line.

```
composer require nwidart/laravel-menus
```

After the package installed, add a new service provider to the `providers` array in `config/app.php` file.

```php
'providers' => [
	Nwidart\Menus\MenusServiceProvider::class,
],
```

Add new alias for `Menu` facade to the `aliases` array at the same file.

```php
'aliases' => array(
	'Menu' => Nwidart\Menus\Facades\Menu::class,
)
```

Then, publish package's assets by running:

```
php artisan vendor:publish --provider="Nwidart\Menus\MenusServiceProvider"
```

## Creating A Menu

You can define your menus in `app/Support/menus.php` file. That file will loaded automatically by this package.

To create a menu, simply call the `create` method from `Menu` facade. The first parameter is the menu name and the second parameter is callback for defining menu items.

```php
Menu::create('navbar', function($menu) {
    // define your menu items here
});
```

### Menu Item

As explained before, we can defining menu item in the callback by accessing `$menu` variable, which the variable is instance of `Nwidart\Menus\MenuBuilder` class.

To defining a plain URL, you can use `->url()` method.
```php
Menu::create('navbar', function($menu) {
    // URL, Title, Attributes
    $menu->url('home', 'Home', ['target' => 'blank']);
});
```

If you have named route, you define the menu item by calling `->route()` method.
```php
Menu::create('navbar', function($menu) {
	$menu->route(
        'users.show', // route name
        'View Profile', // title
        ['id' => 1], // route parameters
        ['target' => 'blank'] // attributes
    );
});
```

You can also defining the menu item via array by calling `->add()` method.
```php
Menu::create('navbar', function($menu) {
    $menu->add([
        'url' => 'about',
        'title' => 'About',
        'attributes' => [
            'target' => '_blank'
        ]
    ]);

    $menu->add([
        'route' => ['profile', ['user' => 'nwidart']],
        'title' => 'Visit My Profile',
        'attributes' => [
            'target' => '_blank'
        ]
    ]);
});
```

### Menu Dropdown

To create a dropdown menu, you can call to `->dropdown()` method and passing the first parameter by title of dropdown and the second parameter by closure callback that retrieve `$sub` variable. The `$sub` variable is the the instance of `Nwidart\Menus\MenuItem` class.

```
Menu::create('navbar', function($menu) {
    $menu->url('/', 'Home');
    $menu->dropdown('Settings', function ($sub) {
        $sub->url('settings/account', 'Account');
        $sub->url('settings/password', 'Password');
        $sub->url('settings/design', 'Design');
    });
});
```

### Menu Dropdown Multi Level

You can also create a dropdown inside dropdown by using `->dropdown()` method. This will allow to to create a multilevel menu items.
```
Menu::create('navbar', function($menu) {
    $menu->url('/', 'Home');
    $menu->dropdown('Account', function ($sub) {
        $sub->url('profile', 'Visit My Profile');
        $sub->dropdown('Settings', function ($sub) {
            $sub->url('settings/account', 'Account');
            $sub->url('settings/password', 'Password');
            $sub->url('settings/design', 'Design');
        });
        $sub->url('logout', 'Logout');
    });
});
```

### Menu Divider

You may also define a divider for each menu item. You can divide between menu item by using `->divider()` method.

``` php
Menu::create('navbar', function($menu) {
    $menu->url('/', 'Home');
    $menu->divider();
    $menu->url('profile', 'Profile')
});
```

### Dropdown Header

You may also add a dropdown header for the specified menu item by using `->header()` method.

``` php
Menu::create('navbar', function($menu) {
    $menu->url('/', 'Home')
    $menu->dropdown('Settings', function ($sub) {
        $sub->header('ACCOUNT');
        $sub->url('/settings/design', 'Design');
        $sub->divider();
        $sub->url('logout', 'Logout');
    });
});
```

### Ordering Menu Item

You may order the menu by specify `order` parameter.

``` php
Menu::create('navbar', function($menu) {
    $menu->url('/', 'Home', 1);
    $menu->route('/', 'About', ['user' => '1'], 2);
    $menu->dropdown('Settings', function ($sub) {
        $sub->header('ACCOUNT');
        $sub->url('/settings/design', 'Design');
        $sub->divider();
        $sub->url('logout', 'Logout');
    }, 3);
});
```

You may also set the order value by calling `->order` method.

``` php
Menu::create('navbar', function($menu) {
    $menu->url('/', 'Home', ['icon' => 'fa fa-dashboard'])->order(1);
    $menu->route('/', 'About', ['user' => '1'], ['icon' => 'fa fa-user'])->order(2);
    $menu->dropdown('Settings', function ($sub) {
        $sub->header('ACCOUNT');
        $sub->url('/settings/design', 'Design');
        $sub->divider();
        $sub->url('logout', 'Logout');
    })->order(3);
});
```

By default ordering feature is disabled. You can enable the `ordering` feature in your config file. Just update value of `ordering` config to `true` and now your menu will ordered by `order` key.

``` php
return [
	'ordering' => true
];
```

You may also enable or disable menu ordering for each menu via `->enableOrdering` and `->disableOrdering` method.

``` php
Menu::create('navbar', function($menu) {
    // disable menu ordering
    $menu->enableOrdering();

    // disable menu ordering
    $menu->disableOrdering();
});
```

### Make many of menus

You can also create a set of menus with different name and menu items.

```php
Menu::create('menu1', function($menu) {
	$menu->route('home', 'Home');
    $menu->url('profile', 'Profile');
});

Menu::create('menu2', function($menu) {
    $menu->route('home', 'Home');
    $menu->url('profile', 'Profile');
});
```

## Menu Presenter

This package includes some presenter classes that are used for converting menus to html. By default the generated menu style is `bootstrap navbar`. But, there are also several different menu styles.

You can apply the menu style via `->style()` method.

``` php
Menu::create('navbar', function($menu) {
    $menu->style('nav-pills');
});
```

Or you can set which presenter to present the menu style via `->setPresenter()` method.

```php
Menu::create('navbar', function($menu) {
    $menu->setPresenter(\Nwidart\Menus\Presenters\Bootstrap\NavTabPresenter::class);
});
```

You can also set which style of presenter when you rendering a menu.

```php
Menu::render('navbar', 'navbar-right');

Menu::render('navbar', \Nwidart\Menus\Presenters\Bootstrap\NavPillsPresenter::class);
```

### The List of Available Menu Presenter Class

| Name          | Presenter Class                                             |
| ------------- |:------------------------------------------------------------|
| `navbar`		| `Nwidart\Menus\Presenters\Bootstrap\NavbarPresenter`       |
| `navbar-right`| `Nwidart\Menus\Presenters\Bootstrap\NavbarRightPresenter`  |
| `nav-pills`	| `Nwidart\Menus\Presenters\Bootstrap\NavPillsPresenter`     |
| `nav-tab`		| `Nwidart\Menus\Presenters\Bootstrap\NavTabPresenter`       |
| `sidebar`     | `Nwidart\Menus\Presenters\Bootstrap\SidebarMenuPresenter`  |
| `navmenu`     | `Nwidart\Menus\Presenters\Bootstrap\NavMenuPresenter`      |

### Make A custom Presenter

You can create your own presenter classes. Make sure your presenter extends the `Nwidart\Menus\Presenters\Presenter` and `implements` the 'Nwidart\Menus\Presenters\PresenterInterface' interface.

For example, this is `zurb-top-bar` presenter. 

```php
use Nwidart\Menus\Presenters\Presenter;

class ZurbTopBarPresenter extends Presenter
{
	/**
	 * {@inheritdoc }
	 */
	public function getOpenTagWrapper()
	{
		return  PHP_EOL . '<section class="top-bar-section">' . PHP_EOL;
	}

	/**
	 * {@inheritdoc }
	 */
	public function getCloseTagWrapper()
	{
		return  PHP_EOL . '</section>' . PHP_EOL;
	}

	/**
	 * {@inheritdoc }
	 */
	public function getMenuWithoutDropdownWrapper($item)
	{
		return '<li'.$this->getActiveState($item).'><a href="'. $item->getUrl() .'">'.$item->getIcon().' '.$item->title.'</a></li>';
	}

	/**
	 * {@inheritdoc }
	 */
	public function getActiveState($item)
	{
		return \Request::is($item->getRequest()) ? ' class="active"' : null;
	}

	/**
	 * {@inheritdoc }
	 */
	public function getDividerWrapper()
	{
		return '<li class="divider"></li>';
	}

	/**
	 * {@inheritdoc }
	 */
	public function getMenuWithDropDownWrapper($item)
	{
		return '<li class="has-dropdown">
		        <a href="#">
		         '.$item->getIcon().' '.$item->title.'
		        </a>
		        <ul class="dropdown">
		          '.$this->getChildMenuItems($item).'
		        </ul>
		      </li>' . PHP_EOL;
		;
	}
}

```
To use this custom presenter, you can use the `setPresenter` method.

```php
Menu::create('zurb-top-bar', function($menu) {
    $menu->setPresenter('ZurbTopBarPresenter');
});
```

### Register A New Menu Style

Menu style is like an alias to a presenter. You can register your style from your costum presenter in the configuration file in `config/menus.php`.

```php
return [
	'navbar'		=>	'Nwidart\Menus\Presenters\Bootstrap\NavbarPresenter',
	'navbar-right'	=>	'Nwidart\Menus\Presenters\Bootstrap\NavbarRightPresenter',
	'nav-pills'		=>	'Nwidart\Menus\Presenters\Bootstrap\NavPillsPresenter',
	'nav-tab'		=>	'Nwidart\Menus\Presenters\Bootstrap\NavTabPresenter',

	'zurb-top-bar'	=>	'ZurbTopBarPresenter',
];
```

Now, you can use a style like this.

```php
Menu::create('zurb-top-bar', function($menu) {
    $menu->style('zurb-top-bar');
});
```

## View Presenter

If you don't like to use presenter classes, you use view presenters instead. We can set which view to present the menus by calling `->setView()` method.

```
Menu::create('navbar', function($menu) {
    $menu->setView('menus::default');
});
```

### The List of Available View Presenter

| View Name                    | Menu Style                   |
| ---------------------------- |:-----------------------------|
| `menus::default`		       | Bootstrap Navbar (default)   |
| `menus::navbar-left`		   | Bootstrap Navbar Left        |
| `menus::navbar-right`		   | Bootstrap Navbar Right       |
| `menus::nav-tabs`		       | Bootstrap Nav Tabs           |
| `menus::nav-tabs-justified`  | Bootstrap Nav Tabs Justified |
| `menus::nav-pills`		   | Bootstrap Nav Pills          |
| `menus::nav-pills-stacked`   | Bootstrap Nav Pills Stacked  |
| `menus::nav-pills-justified` | Bootstrap Nav Pills Justified|
| `menus::menu`                | Plain Menu                   |

<a name="rendering-menu"></a>
## Rendering Menu

To render the menu you can use `render` or `get` method.

```php
Menu::render('navbar');

Menu::get('navbar');
```

You can also set which style to present the menu in the second parameter.

```php
Menu::render('navbar', 'navbar-right');
```

Or you may also set which view to present the menu.

```php
Menu::render('navbar', 'menus::nav-tabs');
```

<a name="menu-inheritance"></a>
<a name="menu-instance"></a>
## The Menu Instance

Sometimes, maybe we need to add a new additional menu from controller or other place. To get an instance of an existing menu, you can use the `instance` method.

```php
$menu = Menu::instance('zurb-top-bar');

// You can also make additions to the menu again

$menu->add(['title' => 'Settings', 'route' => 'settings']);

$menu->url('profile', 'Profile');

$menu->route('settings', 'Settings');
```

<a name="finding-menu-item"></a>
## Finding Menu Item

To find menu item, you can use `findBy` method from `Nwidart\Menus\MenuBuilder` class.

```php
$menu = Menu::instance('sidebar');

$menu->url('profile', 'Profile');

$menuItem = $menu->findBy('title', 'Profile');

// add child menu
$menuItem->url('foo', 'Foo');
```

You may also use `whereTitle` helper method to find a specific menu item. Also, you can add other child menu item in the callback that located in the second argument in `whereTitle` method.

```php
$menu = Menu::instance('sidebar');

$menu->url('profile', 'Profile');

$menu->whereTitle('Profile', function ($sub)
{
	$sub->url('foo', 'Foo');
});

// add childs menu
```

<a name="modifying-menu"></a>
## Modifying Menu

After we create a menu, maybe we need to add other additional menus. You may modifying menu via `->modify` method.

```
Menu::modify('navbar', function($menu)
{
	$menu->add([
		'title' => 'Foo',
		'url' => 'bar',
	]);
});
```
