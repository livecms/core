<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

    // ADMIN AREA
    $router->group(['prefix' => $adminSlug, 'namespace' => 'Backend', 'middleware' => 'auth'], function ($router) {
        //

    });

    // PROFILE AREA

    $userSlug = getSlug('userhome');

    $router->group(['prefix' => $userSlug, 'namespace' => 'User', 'middleware' => 'auth'], function ($router) {
    });
