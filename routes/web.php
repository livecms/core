<?php

return function ($name, $instance) {
    Route::get('/', function () {
        return view('livecms::home');
    })->name('index');

    foreach ($instance['resources'] as $resource) {
        $resource::$baseRoute = 'livecms.'.$name;
        $resource::register();
    }
};

