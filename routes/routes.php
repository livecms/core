<?php

foreach (config('livecms.instances', []) as $name => $instance) {

    $parseUrl = parse_url($instance['base_url'] ?? null);
    if ($domain = $parseUrl['host'] ?? null) {
        $prefix = $parseUrl['path'] ?? '';

        Route::group(['domain' => $domain], function() use ($name, $instance, $prefix) {
            Route::group(['as' => 'livecms.'.$name.'.'], function() use ($name, $instance, $prefix) {
                Route::group(['prefix' => $prefix], function() use ($name, $instance, $prefix) {

                    Route::group(['middleware' => LC_Middleware('web', $name)], function() use ($name, $instance) {
                        // Load Web Route
                        Route::group(['middleware' => LC_Middleware('auth', $name)], function () use ($name, $instance) {
                            $web = include('web.php');
                            $web($name, $instance);

                            // Email Verification
                            if ($instance['verify_email'] ?? false) {
                                Route::get('email/verify', 'LiveCMS\Auth\VerificationController@show')->name('verification.notice');
                                Route::get('email/verify/{id}', 'LiveCMS\Auth\VerificationController@verify')->name('verification.verify');
                                Route::get('email/resend', 'LiveCMS\Auth\VerificationController@resend')->name('verification.resend');
                            }
                        });

                        Route::group([
                            'prefix' => $instance['auth_uri'] ?? 'auth',
                            'middleware' => LC_Middleware('guest', $name),
                            'namespace' => 'LiveCMS\Auth',
                        ], function() use ($name, $instance) {
                            // Authentication Routes...
                            // Login
                            Route::get('/', 'AuthController@showLoginForm')->name('login');
                            Route::post('/', 'AuthController@login')->name('login.post');

                            // Registration Routes...
                            if ($instance['allow_register'] ?? false) {
                                Route::get('register', 'AuthController@showRegistrationForm')->name('register');
                                Route::post('register', 'AuthController@register')->name('register.post');
                            }

                            // Reset Password Request
                            Route::get('reset', 'AuthController@showLinkRequestForm')->name('password.request');
                            Route::post('email', 'AuthController@sendResetLinkEmail')->name('password.email');
                            // Password Reset Routes...
                            Route::get('reset/{token}', 'PasswordController@showResetForm')->name('password.reset');
                            Route::post('reset', 'PasswordController@reset')->name('password.reset.post');


                        });


                        // Logout
                        Route::post('logout', 'LiveCMS\Auth\AuthController@logout')->name('logout');

                    });
                    Route::group(['prefix' => '/api', 'as' => 'api.'], function() use ($instance, $prefix) {
                        include 'api.php';
                    });
                });
            });
        });

    }
}