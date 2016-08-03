<?php

namespace Nwidart\Menus\Tests;

use Collective\Html\HtmlServiceProvider;
use Nwidart\Menus\MenusServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class BaseTestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        // $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            HtmlServiceProvider::class,
            MenusServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('menus', [
            'styles' => [
                'navbar' => \Nwidart\Menus\Presenters\Bootstrap\NavbarPresenter::class,
                'navbar-right' => \Nwidart\Menus\Presenters\Bootstrap\NavbarRightPresenter::class,
                'nav-pills' => \Nwidart\Menus\Presenters\Bootstrap\NavPillsPresenter::class,
                'nav-tab' => \Nwidart\Menus\Presenters\Bootstrap\NavTabPresenter::class,
                'sidebar' => \Nwidart\Menus\Presenters\Bootstrap\SidebarMenuPresenter::class,
                'navmenu' => \Nwidart\Menus\Presenters\Bootstrap\NavMenuPresenter::class,
            ],

            'ordering' => false,
        ]);
    }
}
