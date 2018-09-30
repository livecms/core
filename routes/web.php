<?php

return function ($name, $instance) {
    Route::get('/', function () {
        return view('livecms::layout');
    })->name('index');

    Route::get('home', function () {
        return 'sss';
    });
};

