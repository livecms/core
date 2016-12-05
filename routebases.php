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

use LiveCMS\Notifications\NewContactSubmission;
use Illuminate\Http\Request;
use LiveCMS\Models\Users\User;

$router->get('coming-soon', ['as' => 'coming-soon', function () {
    return view('livecms::coming-soon');
}]);

$router->get('redirect', ['as' => 'redirect', function () {
    return redirect()->to(request()->get('to'));
}]);

// Contact Submission
Route::post('contact',function (Request $request) {
    $sender = [$request->get('email') => $request->get('name')];
    $submission = $request->all();
    $admins = User::adminOnly()->get()->map(function ($admin) use ($sender, $submission) {
        $admin->notify(new NewContactSubmission($sender, $submission));
    });
    return 'success';
});

// PROFILE AREA

$userSlug = getSlug('userhome');
$router->group(['prefix' => $userSlug, 'as' => $userSlug.'.', 'namespace' => 'User', 'middleware' => 'auth'], function ($router) {

    $router->get('/', ['as' => 'user.home', function () {
        $bodyClass = 'skin-blue sidebar-mini sidebar-collapse';
        return view('livecms::user', compact('bodyClass'));
    }]);

    $router->resource('profile', 'ProfileController');
    $router->resource(getSlug('article'), 'ArticleController');
});

// ADMIN AREA
$router->group(['prefix' => $adminSlug, 'as' => $adminSlug.'.', 'namespace' => 'Backend', 'middleware' => 'auth'], function ($router) {
    
    $router->get('/', ['as' => 'admin.home', function () {
        return view('livecms::admin.home');
    }]);


    $router->resource(getSlug('category'), 'CategoryController');
    $router->resource(getSlug('tag'), 'TagController');
    $router->put(getSlug('article').'/{id}/update-featured', 'ArticleController@putUpdateFeatured');
    $router->resource(getSlug('article'), 'ArticleController');
    $router->resource(getSlug('staticpage'), 'StaticPageController');
    $router->resource(getSlug('team'), 'TeamController');
    $router->resource(getSlug('project'), 'ProjectController');
    $router->resource(getSlug('projectcategory'), 'ProjectCategoryController');
    $router->resource(getSlug('client'), 'ClientController');
    $router->resource(getSlug('gallery'), 'GalleryController');
    $router->resource(getSlug('contact'), 'ContactController');

    $router->resource('permalink', 'PermalinkController');
    $router->resource('setting', 'SettingController');
    $router->resource('user', 'UserController');
    $router->resource('site', 'SiteController');

});

// AUTH
Auth::routes();
$router->get('logout', 'Auth\LoginController@logout');

$router->get('register', function () {
    return redirect()->route('user.home');
});
