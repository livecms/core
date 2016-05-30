<?php

namespace LiveCMS\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use LiveCMS\Routing\Redirector;
use LiveCMS\Routing\ResourceRegistrar;
use LiveCMS\Routing\UrlGenerator;
use LiveCMS\Models\Site;

class LiveCMSServiceProvider extends ServiceProvider
{
    protected $baseDir = __DIR__.'/../..';

    protected function bootPublish()
    {
        // View
        $this->loadViewsFrom($this->baseDir.'/views', 'livecms');
        $this->publishes([$this->baseDir.'/views' => base_path('resources/views/vendor/livecms')], 'view');

        // Language
        $this->loadTranslationsFrom($this->baseDir.'/lang', 'livecms');
        $this->publishes([$this->baseDir.'/lang' => base_path('resources/lang')], 'lang');

        // Config
        $this->mergeConfigFrom($this->baseDir.'/config/livecms.php', 'livecms');
        $this->publishes([$this->baseDir.'/config/livecms.php' => config_path('livecms.php')], 'config');

        // Model
        $this->publishes([$this->baseDir.'/models' => app_path('Models')], 'model');
        
        // Controller
        $this->publishes([$this->baseDir.'/controllers' => app_path('Http/Controllers')], 'controller');

        // Migration
        $this->publishes([$this->baseDir.'/database' => base_path('database')], 'database');
        
        
        // Public Asset
        $this->publishes([$this->baseDir.'/public' => public_path('/')], 'public');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootPublish();

        Site::init();

        // Extends Url Generator
        $url = new UrlGenerator(
            app()->make('router')->getRoutes(),
            app()->make('request')
        );
     
        $this->app->bind('url', function () use ($url) {
            return $url;
        });

        $registrar = new ResourceRegistrar($this->app['router']);

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function () use ($registrar) {
            return $registrar;
        });

        // EXTEND ROUTER

        $this->app['router']->group(['namespace' => 'LiveCMS\Controllers'], function ($router) {
            require $this->baseDir.'/routebases.php';
        });

        $this->app['router']->group(['namespace' => 'App\Http\Controllers'], function ($router) {
            require $this->baseDir.'/routes.php';
        });

        // DEBUG BAR
        $routeConfig = [
            'namespace' => 'Barryvdh\Debugbar\Controllers',
            'prefix' => site()->getCurrent()->subfolder.'/'.$this->app['config']->get('debugbar.route_prefix'),
        ];

        $this->app['router']->group($routeConfig, function ($router) {
            $router->get('open', [
                'uses' => 'OpenHandlerController@handle',
                'as' => 'debugbar.openhandler',
            ]);

            $router->get('clockwork/{id}', [
                'uses' => 'OpenHandlerController@clockwork',
                'as' => 'debugbar.clockwork',
            ]);

            $router->get('assets/stylesheets', [
                'uses' => 'AssetController@css',
                'as' => 'debugbar.assets.css',
            ]);

            $router->get('assets/javascript', [
                'uses' => 'AssetController@js',
                'as' => 'debugbar.assets.js',
            ]);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require $this->baseDir.'/helpers.php';
    }
}
