<?php

namespace Nwidart\Menus\Tests;

use Nwidart\Menus\MenuItem;

class MenuItemTest extends BaseTestCase
{
    /** @test */
    public function it_can_make_an_empty_menu_item()
    {
        $menuItem = MenuItem::make([]);

        $this->assertInstanceOf(MenuItem::class, $menuItem);
    }

    /** @test */
    public function it_can_set_properties_on_menu_item()
    {
        $properties = [
            'url' => 'my.url',
            'route' => 'my.route',
            'title' => 'My Menu item',
            'name' => 'my-menu-item',
            'icon' => 'fa fa-user',
            'parent' => 1,
            'attributes' => [],
            'active' => false,
            'order' => 1,
        ];
        $menuItem = MenuItem::make($properties);

        $this->assertEquals($properties, $menuItem->getProperties());
    }

    /** @test */
    public function it_can_fill_a_menu_item_with_allowed_properties()
    {
        $properties = [
            'url' => 'my.url',
            'route' => 'my.route',
            'title' => 'My Menu item',
            'name' => 'my-menu-item',
            'icon' => 'fa fa-user',
            'parent' => 1,
            'attributes' => [],
            'active' => false,
            'order' => 1,
        ];
        $menuItem = MenuItem::make($properties);

        $this->assertEquals('my.url', $menuItem->url);
        $this->assertEquals('my.route', $menuItem->route);
        $this->assertEquals('My Menu item', $menuItem->title);
        $this->assertEquals('my-menu-item', $menuItem->name);
        $this->assertEquals('fa fa-user', $menuItem->icon);
        $this->assertSame(1, $menuItem->parent);
        $this->assertSame([], $menuItem->attributes);
        $this->assertFalse($menuItem->active);
        $this->assertSame(1, $menuItem->order);
    }

    /** @test */
    public function it_can_set_icon_via_attributes()
    {
        $menuItem = MenuItem::make(['attributes' => ['icon' => 'fa fa-user']]);

        $this->assertEquals('fa fa-user', $menuItem->icon);
    }
}
