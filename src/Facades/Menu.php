<?php

namespace Nwidart\Menus\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use Nwidart\Menus\MenuBuilder;

/**
 * @method static MenuBuilder make($name, Closure $callback) Make new menu.
 * @method static MenuBuilder create($name, Closure $resolver) Create new menu.
 * @method static bool has(string $name) check if the menu exists.
 * @method static string|null instance(string $name) Get instance of the given meu if exists.
 * @method static void modify(string $name, Closure $callback) Modify a specific menu.
 * @method static string|null get(string $name, string $presenter = null, array $bindings = []) Render the menu tag by given name.
 * @method static string|null render(string $name, string $presenter = null, array $bindings = []) Render the menu tag by given name.
 * @method static mixed style() Get a stylesheet for enable multilevel menu.
 * @method static array all() Get all Menus.
 * @method static int count() Get Count from all menus.
 * @method static void destroy() Empty the current menus.
 */
class Menu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'menus';
    }
}
