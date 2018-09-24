<?php

return [

    'instances' => [

        // You can make multiple instances
        'main' => [
            // Theme
            'theme' => 'gantelella',

            // base url
            'base_url' => env('CMS_URL', 'http://localhost'),

            // URI to access CMS
            'uris' => [
                // login uri
                'login' => 'login',
            ],

            'middleware' => [
                // list of guest middleware
                'guest' => [
                    LiveCMS\Middleware\RedirectIfAuthenticated::class,
                ],

                // list of auth middleware
                'auth' => [
                    LiveCMS\Middleware\Authenticate::class,
                ],
            ],

            // You can use your own model
            'user_model' => LiveCMS\User\UserModel::class,

            // resources
            'resources' => [
                'Page' => LiveCMS\Resources\PageResource::class,
            ],
        ],

        // You can name it 'second' or whatever you want
        'second' => [
            //
        ]
    ],

    'template_path' => resource_path('livecms/templates'),

    // view files are automaticly generated when you call 'artisan livecms:template'
    // but you can modify it the behaviour by put the view path in your app directory
    // instead of in the vendor directory, e.g.
    // 'view_path' => resource_path('livecms/views'),
    'view_path' => __DIR__.'/../views',

    'guard' => [
        // guard name
        'name' => 'livecms',
        'driver' => 'session',
        // Guard provider
        'provider' => [
            'driver' => 'eloquent',
            'model' => LiveCMS\User\UserModel::class,
        ],
    ],
];
