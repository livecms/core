<?php

return [
    
    'image' => [
    
        'driver' => 'gd',
    ],
    
    'thumbnailer' => [

        // 'thumb' => '_thumb_',

        // 'size' => '300x300',
    ],

    'uploader' => [
        
        // 'override' => false,

        // 'modelOverride' => true,

        // 'baseFolder' => 'public/uploads',

        // 'folder' => '',

    ],

    'emailer' => [

        'from' => [
            'address' => env('EMAIL_ADDRESS', null),
            'name' => env('EMAIL_NAME', null),
        ],
        'subject' => 'Hello',
        'siteurl' => env('SITE_URL', env('APP_URL')),
        'sitename' => env('SITE_NAME', null),
        'siteslogan' => env('SITE_SLOGAN', null),

        'activation' => ['subject' => 'Account Activation'],

        'welcome' => [],

        'alert' => ['parent' => 'welcome', 'subject' => 'alert'],
    
        'invoice' => [],
    ],

    'domain' => env('APP_DOMAIN', 'livecms.dev'),

    'routing' => [

        'namespace' => 'LiveCMS\Controllers',

        'slugs' => [
            'admin'         => '@',
            'article'       => 'a',
            'category'      => 'cat',
            'tag'           => 'tag',
            'staticpage'    => 'p',
            'team'          => 't',
            'project'       => 'x',
            'projectcategory'   => 'x-cat',
            'client'        => 'c',
            'gallery'       => 'g',
            'contact'       => 'contact',
            'userhome'      => 'me',
            'profile'       => 'profile',
        ],
    ],

    'users' => [
        'defaultpassword' => 'passwordlivecms',
    ],

    'themes' => [
        'admin' => 'adminLTE',
        'front' => 'timer',
    ],

    'menus' => [

        'admin' => [
            [   'title' => 'post', 'icon' => 'pencil',
                'uri' => [
                    ['uri' => 'article', 'title' => 'article', 'icon' => 'files-o'],
                    ['uri' => 'category', 'title' => 'category', 'icon' => 'list'],
                    ['uri' => 'tag', 'title' => 'tag', 'icon' => 'tag'],
                ],
            ],
            [   'title' => 'clientandproject', 'icon' => 'briefcase',
                'uri' => [
                    ['uri' => 'client', 'title' => 'client', 'icon' => 'users'],
                    ['uri' => 'projectcategory', 'title' => 'projectcategory', 'icon' => 'list'],
                    ['uri' => 'project', 'title' => 'project', 'icon' => 'briefcase'],
                ],
            ],
            ['uri' => 'staticpage', 'title' => 'staticpage', 'icon' => 'file-o'],
            ['uri' => 'permalink', 'title' => 'permalink', 'icon' => 'link'],
            ['uri' => 'team', 'title' => 'team', 'icon' => 'user-plus'],
            ['uri' => 'gallery', 'title' => 'gallery', 'icon' => 'image'],
            ['uri' => 'user', 'title' => 'user', 'icon' => 'users'],
            ['uri' => 'contact', 'title' => 'contact', 'icon' => 'phone'],
            ['uri' => 'setting', 'title' => 'setting', 'icon' => 'cog'],
            ['uri' => 'site', 'title' => 'site', 'icon' => 'globe'],
        ],
      
        'user' => [
            ['uri' => 'profile', 'title' => 'profile', 'icon' => 'user'],
            ['uri' => 'article', 'title' => 'article', 'icon' => 'pencil'],
        ],

    ],
];
