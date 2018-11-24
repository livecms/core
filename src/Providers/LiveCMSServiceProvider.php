<?php

namespace LiveCMS\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use ReflectionClass;

class LiveCMSServiceProvider extends ServiceProvider
{
    protected $consoles = [
        \LiveCMS\Commands\TemplateCommand::class,
        //
    ];

    protected function baseDir()
    {
        return __DIR__ . '/../..';
    }

    protected function loadConsole()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                $this->consoles
            );
        }
    }

    protected function bootPublish()
    {
        // Route
        $this->loadRoutesFrom($this->baseDir().'/routes/routes.php');

        // Template
        $this->publishes([$this->baseDir().'/templates/' => resource_path('templates')], 'template');

        // Publish View and 
        $this->loadViewsAndAssets();

        $this->loadViewsFrom($this->baseDir().'/views/', 'livecms');
        // Language
        // $this->loadTranslationsFrom($this->baseDir().'/lang', 'livecms');
        // $this->publishes([$this->baseDir().'/lang' => base_path('resources/lang/vendor/livecms')], 'lang');

        // Validation messages
        // $this->publishes([$this->baseDir().'/validations' => base_path('resources/lang')], 'validation');

        // Config
        $this->publishes([$this->baseDir().'/config/livecms.php' => config_path('livecms.php')], 'config');

        // Controller
        // $this->publishes([$this->baseDir().'/controllers' => app_path('Http/Controllers')], 'controller');

        // Migration
        // $this->publishes([$this->baseDir().'/database' => base_path('database')], 'database');

        // Notification
        // $this->publishes([$this->baseDir().'/notifications' => base_path('resources/views/vendor/notifications')], 'notification');


    }

    protected function loadViewsAndAssets()
    {
        $templatePath = config('livecms.template_path');
        $templatePath = is_dir($templatePath) ? $templatePath : $this->baseDir().'/templates';
        $this->loadViewsFrom($templatePath, 'livecms-templates');

        $viewPath = config('livecms.view_path');
        $viewPath = is_dir($viewPath) ? $viewPath : $this->baseDir().'/views';
        $this->loadViewsFrom($viewPath, 'livecms');

        $this->loadAssets($templatePath);
    }

    protected function loadAssets($templatePath)
    {
        foreach (array_pluck(config('livecms.instances'), 'theme') as $theme) {
            if (!$theme) {
                continue;
            }

            $themeDir = $templatePath.'/'.$theme;

            $manifest = array_except(scandir($publicDir = $themeDir.'/assets/'), [0, 1]);
            array_map(function ($asset) use ($publicDir, $theme, &$assets) {
                return $assets[$publicDir.$asset] = public_path('vendor/livecms/'.$theme.'/'.$asset);
            }, $manifest);
        }

        // Publish Asset
        $this->publishes($assets, 'public');
    }

    protected function loadMiddleware($instance = null)
    {
        $router = $this->app['router'];

        $config = $instance ? 'livecms.instances.'.$instance.'.middleware' : 'livecms.middleware';
        foreach(config($config, []) as $name => $middlewares) {
            $name = Str::replaceLast('.middleware', '', $config).'.'.$name;
            $midName = $instance ? Str::replaceFirst('livecms.', '', $name) : $name;
            $wrapped = [];
            info($middlewares);
            foreach ($middlewares as $key => $middleware) {
                if (!class_exists($middleware)) {
                    $wrapped[] = $middleware;
                } else {
                    $reflectionClass = new ReflectionClass($middleware);
                    if ($reflectionClass->isInstantiable()) {
                        $router->aliasMiddleware($md = $midName.'.'.$key, $middleware);
                        $wrapped[] = $md;
                    }
                }
            }
            config(['livecms.middleware.wrapped.'.$midName => $wrapped]);
        }
        if ($instance == null) {
            foreach (config('livecms.instances') as $name => $instance) {
                $this->loadMiddleware($name);
            }
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Commands
        $this->loadConsole();

        // Publish 
        $this->bootPublish();

        // Extends Url Generator
        // $this->app->bind('url', function ($app) {
        //     return new UrlGenerator(
        //         $app['router']->getRoutes(),
        //         $app['request']
        //     );
        // });

        // $this->app->bind('Illuminate\Routing\ResourceRegistrar', function ($app) {
        //     return new ResourceRegistrar($app['router']);
        // });

        // try {

            // Site::init();

            // $router = $this->app['router'];
            // $config = $this->app['config'];

            // // DEBUG BAR

            // liveCMSRouter($router, function ($router, $adminSlug, $subDomain, $subFolder) use ($config) {
            //     // EXTEND ROUTER
            //     $router->group(['namespace' => 'LiveCMS\Controllers'], function ($router) use ($adminSlug, $subDomain, $subFolder) {
            //         require $this->baseDir().'/routebases.php';
            //     });
            // });

        // } catch (\Exception $e) {
            // throw new \Exception('Error in LiveCMSServiceProvider : '.$e->getMessage());
        // }

    }

    protected function registerGuard($instance = null)
    {
        $config = $this->app['config'];
        $key = $instance ? 'livecms.instances.'.$instance.'.guard' : 'livecms.guard';

        if ($guardConfig = $config->get($key, null)) {

            if (! (is_string($guardConfig) && array_key_exists($guardConfig, $config->get('auth.guards', [])))) {

                if (array_key_exists($guardConfig['name'], array_keys($config->get('auth.guards', [])))) {
                    throw new \Exception("You can not create a new guard that has been existed : {$guardConfig['name']}, in instance : {$instance}. Check your `livecms` config file.", 1);
                }

                $guard = [
                    'driver' => $guardConfig['driver'],
                    'provider' => $providerName = ($guardName = $guardConfig['name']).'-users',
                ];

                $provider = $guardConfig['provider'];

                $config->set('auth.guards.'.$guardName, $guard);
                $config->set('auth.providers.'.$providerName, $provider);
            }
        }

        if ($instance === null) {
            foreach ($config->get('livecms.instances', []) as $instance => $value) {
                $this->registerGuard($instance);
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $config = $this->app['config'];
        if (!$config->get('livecms.middleware.wrapped')) {
            $this->mergeConfigFrom($this->baseDir().'/config/livecms.php', 'livecms');
            $this->registerGuard();
            $this->loadMiddleware();
        }
        // Helper
        require $this->baseDir().'/helpers/helper.php';
    }
}
