<?php

namespace Nwidart\Menus;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class MenusServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->registerNamespaces();
        $this->registerMenusFile();
    }

    /**
     * Require the menus file if that file is exists.
     */
    public function registerMenusFile()
    {
        if (file_exists($file = app_path('Support/menus.php'))) {
            require $file;
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerHtmlPackage();

        $this->app->singleton('menus', function ($app) {
            return new Menu($app['view'], $app['config']);
        });
    }

    /**
     * Register "iluminate/html" package.
     */
    private function registerHtmlPackage()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');

        $aliases = [
            'HTML' => 'Collective\Html\HtmlFacade',
            'Form' => 'Collective\Html\FormFacade',
        ];

        AliasLoader::getInstance($aliases)->register();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['menus'];
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $viewsPath = __DIR__ . '/../views';
        $this->mergeConfigFrom($configPath, 'menus');
        $this->loadViewsFrom($viewsPath, 'menus');

        $this->publishes([
            $configPath => config_path('menus.php'),
        ], 'config');

        $this->publishes([
            $viewsPath => base_path('resources/views/vendor/nwidart/menus'),
        ], 'views');
    }
}
