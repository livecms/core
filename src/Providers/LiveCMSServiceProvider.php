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
    protected function baseDir()
    {
        return __DIR__ . '/../..';
    }

    protected function bootPublish()
    {
        // View
        $this->loadViewsFrom($this->baseDir().'/views', 'livecms');
        $this->publishes([$this->baseDir().'/views' => base_path('resources/views/vendor/livecms')], 'view');

        // Language
        $this->loadTranslationsFrom($this->baseDir().'/lang', 'livecms');
        $this->publishes([$this->baseDir().'/lang' => base_path('resources/lang/vendor/livecms')], 'lang');

        // Config
        $this->publishes([$this->baseDir().'/config/livecms.php' => config_path('livecms.php')], 'config');

        // Model
        $this->publishes([$this->baseDir().'/models' => app_path('Models')], 'model');
        
        // Controller
        $this->publishes([$this->baseDir().'/controllers' => app_path('Http/Controllers')], 'controller');

        // Migration
        $this->publishes([$this->baseDir().'/database' => base_path('database')], 'database');
        
        
        // Public Asset
        $this->publishes([$this->baseDir().'/public' => public_path('/')], 'public');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootPublish();

        // Extends Url Generator
        $this->app->bind('url', function ($app) {
            return new UrlGenerator(
                $app['router']->getRoutes(),
                $app['request']
            );
        });

        $this->app->bind('Illuminate\Routing\ResourceRegistrar', function ($app) {
            return new ResourceRegistrar($app['router']);
        });

        try {

            Site::init();

            $router = $this->app['router'];
            $config = $this->app['config'];

            // DEBUG BAR

            liveCMSRouter($router, function ($router, $adminSlug, $subDomain, $subFolder) use ($config) {

                $routeConfig = [
                    'namespace' => 'Barryvdh\Debugbar\Controllers',
                    'prefix' => $config->get('debugbar.route_prefix'),
                ];

                $router->group($routeConfig, function ($router) {
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

                // EXTEND ROUTER
                $router->group(['namespace' => 'LiveCMS\Controllers'], function ($router) use ($adminSlug, $subDomain, $subFolder) {
                    require $this->baseDir().'/routebases.php';
                });

                $router->group(['namespace' => $config->get('livecms.routing.namespace')], function ($router) use ($adminSlug, $subDomain, $subFolder) {
                    require $this->baseDir().'/routes.php';
                });
            });

            
            
        } catch (\Exception $e) {
            throw new \Exception('Error in LiveCMSServiceProvider : '.$e->getMessage());
        }

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->baseDir().'/config/livecms.php', 'livecms');
        
        require $this->baseDir().'/helpers.php';
    }
}
