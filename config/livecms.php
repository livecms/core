<?php

return [
    'domain' => env('APP_DOMAIN', 'livecms.dev'),

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
                    ['uri' => getSlug('article'), 'title' => 'article', 'icon' => 'files-o'],
                    ['uri' => getSlug('category'), 'title' => 'category', 'icon' => 'list'],
                    ['uri' => getSlug('tag'), 'title' => 'tag', 'icon' => 'tag'],
                ],
            ],
            [   'title' => 'clientandproject', 'icon' => 'briefcase',
                'uri' => [
                    ['uri' => getSlug('client'), 'title' => 'client', 'icon' => 'users'],
                    ['uri' => getSlug('projectcategory'), 'title' => 'projectcategory', 'icon' => 'list'],
                    ['uri' => getSlug('project'), 'title' => 'project', 'icon' => 'briefcase'],
                ],
            ],
            ['uri' => getSlug('staticpage'), 'title' => 'staticpage', 'icon' => 'file-o'],
            ['uri' => 'permalink', 'title' => 'permalink', 'icon' => 'link'],
            ['uri' => getSlug('team'), 'title' => 'team', 'icon' => 'user-plus'],
            ['uri' => getSlug('gallery'), 'title' => 'gallery', 'icon' => 'image'],
            ['uri' => 'user', 'title' => 'user', 'icon' => 'users'],
            ['uri' => getSlug('contact'), 'title' => 'contact', 'icon' => 'phone'],
            ['uri' => 'setting', 'title' => 'setting', 'icon' => 'cog'],
            ['uri' => 'site', 'title' => 'site', 'icon' => 'globe'],
        ],
      
        'user' => [
            ['uri' => getSlug('profile'), 'title' => 'profile', 'icon' => 'user'],
            ['uri' => getSlug('article'), 'title' => 'article', 'icon' => 'pencil'],
        ],

    ],
];
