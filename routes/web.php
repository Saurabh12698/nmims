<?php

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::view('/', 'welcome');

Route::prefix('admin')->group(function () {
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::get('/home', 'AdminController@index')->name('admin.home');
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/dashboard', 'StoryController@index');
        Route::post('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
        Route::resource('/story' , 'StoryController');
    });
});
