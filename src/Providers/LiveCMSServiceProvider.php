<?php

namespace LiveCMS\Providers;

use Illuminate\Support\ServiceProvider;

class LiveCMSServiceProvider extends ServiceProvider
{
    protected $defer = true;

    protected $consoles = [
        \LiveCMS\Commands\TemplateCommand::class,
        //
    ];

    protected function baseDir()
    {
        return __DIR__ . '/../..';
    }

    protected function loadConsoles()
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
        $this->publishes([$this->baseDir().'/livecms/templates/' => resource_path('templates')], 'template');

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


        // Helper
        require $this->baseDir().'/helpers/helper.php';
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
        $assets = [$templatePath.'/fonts' => public_path('vendor/livecms/fonts')];
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

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Commands
        $this->loadConsoles();

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

    protected function registerGuard()
    {
        $guardConfig = $this->app['config']->get('livecms.guard', []);

        $guard = [
            'driver' => $guardConfig['driver'],
            'provider' => $providerName = ($guardName = $guardConfig['name']).'-users',
        ];

        $provider = $guardConfig['provider'];

        $this->app['config']->set('auth.guards.'.$guardName, $guard);
        $this->app['config']->set('auth.providers.'.$providerName, $provider);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        dd(config('auth'));
        $this->mergeConfigFrom($this->baseDir().'/config/livecms.php', 'livecms');
        // $this->registerGuard();
    }
}
