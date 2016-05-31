# Project LiveCMS - CORE

# How To Install :

1. Create Laravel Project (5.2.*)
    ````
         composer create-project laravel/laravel liveCMS --prefer-dist 
    ````

2. Edit composer.json
    Change  :
    ````    
        "config": {
            "preferred-install": "dist"
        }
        ````

    with :
    ````
        "minimum-stability": "dev",
        "prefer-stable": true
    ````


3. After finish, add livecms core in your project
    ````
         cd liveCMS 
         composer require livecms/core "dev-master"
    ````

4. open file config/app.php
    Change :
    ````
            App\Providers\AppServiceProvider::class,
            App\Providers\AuthServiceProvider::class,
            App\Providers\EventServiceProvider::class,
            App\Providers\RouteServiceProvider::class,
    ````

    with :
    ````
            App\Providers\AppServiceProvider::class,
            LiveCMS\Providers\LiveCMSServiceProvider::class,
            Barryvdh\Debugbar\ServiceProvider::class,
            App\Providers\AuthServiceProvider::class,
            App\Providers\EventServiceProvider::class,
            App\Providers\RouteServiceProvider::class,

            Yajra\Datatables\DatatablesServiceProvider::class,
            LiveCMS\Collective\Html\HtmlServiceProvider::class,
            UxWeb\SweetAlert\SweetAlertServiceProvider::class,
            LiveCMS\Support\Thumbnailer\ThumbnailerServiceProvider::class,
            RoketId\ImageMax\ImageMaxServiceProvider::class,
    ````

4. Publish vendor :
    ````
         php artisan vendor:publish --force
    ````

5. Open app/Http/Kernel.php and edit :

    add this line to :
    ```` 
        protected $middleware = [
            ...

            \LiveCMS\Middleware\GlobalParamsMiddleware::class,
            \LiveCMS\Middleware\HttpsMiddleware::class,
        ];
    ````

    change :
    ````
        protected $routeMiddleware = [
            'auth' => \App\Http\Middleware\Authenticate::class,
            change to :
            'auth' => \LiveCMS\Middleware\Authenticate::class,

            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            change to :
            'guest' => \LiveCMS\Middleware\RedirectIfAuthenticated::class,

            add this line :
            'model' => \LiveCMS\Middleware\ModelMiddleware::class,
        ];
    ````

6. Open config/auth.php
    Edit :
    ````
        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => LiveCMS\Models\Users\User::class,
            ],

        .......

        'passwords' => [
            'users' => [
                'provider' => 'users',
                'email' => 'livecms::auth.emails.password',
                'table' => 'password_resets',
                'expire' => 60,
            ],
        ],
    ````


7. Update your .env
    add based on what your domain url:
    ````
        APP_DOMAIN=yourdomain.com
    ````


Visit https://github.com/livecms/LiveCMS for more info.

