<?php

return function ($name, $instance) {
    Route::get('/', function () {
        return view('livecms::home');
    })->name('index');

    Route::group(['prefix' => 'media-library', 'as' => 'media-library.'], function() {
        Route::get(
            'open/{editor?}',
            [
                'as' => 'open',
                'uses' => '\LiveCMS\MediaLibrary\MediaLibraryController@open'
            ]
        );
        Route::get(
            '{type}/get/{limit?}',
            [
                'as' => 'get',
                'uses' => '\LiveCMS\MediaLibrary\MediaLibraryController@get'
            ]
        );
        Route::post(
            '{type}/upload',
            [
                'as' => 'upload',
                'uses' => '\LiveCMS\MediaLibrary\MediaLibraryController@upload'
            ]
        );
        Route::put(
            '{type}/{identifier}/rename',
            [
                'as' => 'rename',
                'uses' => '\LiveCMS\MediaLibrary\MediaLibraryController@rename'
            ]
        );
        Route::delete(
            '{type}/{identifier}/delete', 
            [
                'as' => 'delete',
                'uses' => '\LiveCMS\MediaLibrary\MediaLibraryController@delete'
            ]
        );
    });

    foreach ($instance['resources'] as $resource) {
        $baseRoute = 'livecms.'.$name;
        $resource::register($baseRoute);
    }
};

