<?php

namespace Nwidart\Menus\Traits;

use Closure;

trait CanHide
{
    /**
     * @var Closure
     */
    protected $hideWhen;

    /**
     * Set hide condition for current menu item.
     *
     * @param  Closure
     * @return boolean
     */
    public function hideWhen(Closure $callback)
    {
        $this->hideWhen = $callback;

        return $this;
    }

    /**
     * Determine whether the menu item is hidden.
     *
     * @return boolean
     */
    public function hidden()
    {
        if (is_null($this->hideWhen)) {
            return false;
        }

        return call_user_func($this->hideWhen) == true;
    }
}
