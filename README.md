# Project LiveCMS - CORE

# How To Install :

1. Create Laravel Project (5.2.*)
```` composer create-project laravel/laravel liveCMS --prefer-dist 

2. Edit composer.json
Change  :
````    
    "config": {
        "preferred-install": "dist"
    }

with :
````
    "minimum-stability": "dev",
    "prefer-stable": true


3. After finish, add livecms core in your project
```` cd liveCMS 
     composer require livecms/core "dev-master"

4. open file config/app.php
Change :
````
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

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

Visit https://github.com/livecms/LiveCMS for more info.

