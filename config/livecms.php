<?php

return [

    'instances' => [

        // You can make multiple instances
        'main' => [
            // Name of instance
            'name' => 'Live CMS',

            'slogan' => 'Cool CMS For Laravel',

            // Theme
            'theme' => 'gantelella',

            // base url
            'base_url' => env('CMS_URL', 'http://localhost'),

            'auth_uri' => null,

            'allow_register' => true,

            'verify_email' => true,

            // You can use your specific guard, just the same format with default guard or just the same
            // use null if remain the same
            // Define instance own guard, to deal with different login session
            'guard' => null,

            'middleware' => [
                // list of web middleware
                'web' => [
                    // custom guest middleware for this instance
                ],

                // list of api middleware
                'api' => [
                    // custom guest middleware for this instance
                ],

                // list of guest middleware
                'guest' => [
                    // custom guest middleware for this instance
                ],

                // list of auth middleware
                'auth' => [
                    // custom auth middleware for this instance
                ],
            ],

            // resources
            'resources' => [
                'Page' => LiveCMS\Resources\PageResource::class,
            ],
        ],

        // You can name it 'second' or whatever you want
        'second' => [
            //
        ],
    ],

    'template_path' => resource_path('livecms/templates'),

    // view files are automaticly generated when you call 'artisan livecms:template'
    // but you can modify it the behaviour by put the view path in your app directory
    // instead of in the vendor directory, e.g.
    // 'view_path' => resource_path('livecms/views'),
    'view_path' => __DIR__.'/../views',

    // you can use guard that is defined in auth config or create the new one here.
    'guard' => [
        // guard name
        'name' => 'livecms',
        'driver' => 'session',
        // Guard provider
        'provider' => [
            'driver' => 'eloquent',
            'model' => LiveCMS\Auth\UserModel::class,
        ],
    ],

    'middleware' => [
        // web
        'web' => [
            'web', // web is name of middleware group defined by laravel, see App/Http/Kernel.php file.
            LiveCMS\Middleware\Middleware::class,
        ],

        // Api
        'api' => [
            //
        ],

        // list of default guest middleware
        'guest' => [
            LiveCMS\Middleware\RedirectIfAuthenticated::class,
        ],

        // list of default auth middleware
        'auth' => [
            LiveCMS\Middleware\Authenticate::class,
        ],
    ],

    // set true if you only want to logout specific instance not global logout.
    'independent_logout' => true,
];
