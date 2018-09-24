# Project LiveCMS - CORE

## Note :
Only works with Laravel version 5.5 or above.

##For Laravel 5.3, please checkout branch V1
##For Laravel 5.2, please checkout branch V0

# How To Install :

1. Create Laravel Project (5.5.\*)
    ````
         composer create-project laravel/laravel liveCMS "5.5.*" --prefer-dist
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
         composer require livecms/core
    ````

4. Update your .env
    update based on what your site url:
    ````
        APP_URL=yourdomain.com
    ````

5. Artisan Optimize and do Migrate
    ````
        php artisan optimize
        php artisan migrate --seed
    ````

6. Edit your RouteServiceProvider
    Update mapWebRoutes() method in your app/Providers/RouteServiceProvider.php
    ````
        protected function mapWebRoutes()
        {
            Route::group([
                'middleware' => 'web',
                'namespace' => $this->namespace,
            ], function ($router) {
                liveCMSRouter($router, function ($router, $adminSlug, $subDomain, $subFolder) {
                    require base_path('routes/web.php');
                    frontendRoute($router);
                });
            });
        }
    ````

8. Login
    visit : http://yourdomain/login

    default username / password 
    
    1. Admin :
        email : admin@livecms.dev
        password : admin

    2. Super Admin :
        email : super@livecms.dev
        password : admin

Visit https://github.com/livecms/LiveCMS for more info.
