<?php

foreach (config('livecms.instances', []) as $name => $instance) {

    $parseUrl = parse_url($instance['base_url'] ?? null);
    if ($domain = $parseUrl['host'] ?? null) {
        $prefix = $parseUrl['path'] ?? '';

        Route::group(['domain' => $domain], function() use ($name, $instance, $prefix) {
            Route::group(['as' => 'livecms.'.$name.'.'], function() use ($name, $instance, $prefix) {
                Route::group(['prefix' => $prefix], function() use ($name, $instance, $prefix) {

                    Route::group(['middleware' => ['web', LiveCMS\Middleware\Middleware::class]], function() use ($name, $instance) {
                        // Load Web Route
                        Route::group(['middleware' => $instance['middleware']['auth']], function () use ($name, $instance) {
                            $web = include('web.php');
                            $web($name, $instance);
                        });

                        $login = $instance['uris']['login'];
                        Route::group(['middleware' => $instance['middleware']['guest']], function() use ($login) {
                            // Authentication Routes...
                            Route::get($login, 'LiveCMS\Controllers\AuthController@showLoginForm')->name('login');
                            Route::post($login, 'LiveCMS\Controllers\AuthController@login')->name('login.post');
                            Route::get('password/reset', 'LiveCMS\Controllers\AuthController@showLinkRequestForm')->name('password.request');
                            Route::post('password/email', 'LiveCMS\Controllers\AuthController@sendResetLinkEmail')->name('password.email');
                            // Password Reset Routes...
                            Route::get('password/reset/{token}', 'LiveCMS\Controllers\PasswordController@showResetForm')->name('password.reset');
                            Route::post('password/reset', 'LiveCMS\Controllers\PasswordController@reset')->name('password.reset.post');
                        });

                        Route::post('logout', 'LiveCMS\Controllers\AuthController@logout')->name('logout');

                    });
                    Route::group(['prefix' => '/api', 'as' => 'api.'], function() use ($instance, $prefix) {
                        include 'api.php';
                    });
                });
            });
        });

    }
}