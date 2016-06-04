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
            [   'title' => trans('livecms::livecms.post'), 'icon' => 'pencil',
                'uri' => [
                    ['uri' => getSlug('article'), 'title' => trans('livecms::livecms.article'), 'icon' => 'files-o'],
                    ['uri' => getSlug('category'), 'title' => trans('livecms::livecms.category'), 'icon' => 'list'],
                    ['uri' => getSlug('tag'), 'title' => trans('livecms::livecms.tag'), 'icon' => 'tag'],
                ],
            ],
            [   'title' => trans('livecms::livecms.clientandproject'), 'icon' => 'briefcase',
                'uri' => [
                    ['uri' => getSlug('client'), 'title' => trans('livecms::livecms.client'), 'icon' => 'users'],
                    ['uri' => getSlug('projectcategory'), 'title' => trans('livecms::livecms.projectcategory'), 'icon' => 'list'],
                    ['uri' => getSlug('project'), 'title' => trans('livecms::livecms.project'), 'icon' => 'briefcase'],
                ],
            ],
            ['uri' => getSlug('staticpage'), 'title' => trans('livecms::livecms.staticpage'), 'icon' => 'file-o'],
            ['uri' => 'permalink', 'title' => 'Permalink', 'icon' => 'link'],
            ['uri' => getSlug('team'), 'title' => trans('livecms::livecms.team'), 'icon' => 'user-plus'],
            ['uri' => getSlug('gallery'), 'title' => trans('livecms::livecms.gallery'), 'icon' => 'image'],
            ['uri' => 'user', 'title' => trans('livecms::livecms.user'), 'icon' => 'users'],
            ['uri' => getSlug('contact'), 'title' => trans('livecms::livecms.contact'), 'icon' => 'phone'],
            ['uri' => 'setting', 'title' => 'Setting', 'icon' => 'cog'],
            ['uri' => 'site', 'title' => trans('livecms::livecms.site'), 'icon' => 'globe'],
        ],
      
        'user' => [
            ['uri' => getSlug('profile'), 'title' => trans('livecms::livecms.profile'), 'icon' => 'user'],
            ['uri' => getSlug('article'), 'title' => trans('livecms::livecms.article'), 'icon' => 'pencil'],
        ],

    ],
];
