<?php

namespace Nwidart\Menus\Tests;

use Nwidart\Menus\Menu;
use Nwidart\Menus\MenuBuilder;

class MenuTest extends BaseTestCase
{
    /**
     * @var Menu
     */
    private $menu;

    public function setUp()
    {
        parent::setUp();
        $this->menu = app(Menu::class);
    }
    /** @test */
    public function it_generates_an_empty_menu()
    {
        $this->menu->create('test', function (MenuBuilder $menu) {
        });

        $expected = <<<TEXT

<ul class="nav navbar-nav">

</ul>

TEXT;

        self::assertEquals($expected, $this->menu->get('test'));
    }
}
