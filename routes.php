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
liveCMSRouter($router, function ($router, $adminSlug, $subDomain, $subFolder) {

    // ADMIN AREA
    $router->group(['prefix' => $adminSlug, 'namespace' => 'Backend', 'middleware' => 'auth'], function ($router) {
        
        $router->resource(getSlug('category'), 'CategoryController');
        $router->resource(getSlug('tag'), 'TagController');
        $router->resource(getSlug('article'), 'ArticleController');
        $router->resource(getSlug('staticpage'), 'StaticPageController');
        $router->resource(getSlug('team'), 'TeamController');
        $router->resource(getSlug('project'), 'ProjectController');
        $router->resource(getSlug('projectcategory'), 'ProjectCategoryController');
        $router->resource(getSlug('client'), 'ClientController');
        $router->resource(getSlug('gallery'), 'GalleryController');
        $router->resource(getSlug('contact'), 'ContactController');

    });

    // PROFILE AREA

    $userSlug = getSlug('userhome');

    $router->group(['prefix' => $userSlug, 'namespace' => 'User', 'middleware' => 'auth'], function ($router) {
        $router->resource(getSlug('article'), 'ArticleController');
    });

});
