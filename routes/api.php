<?php

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/profile' , 'API\UserController@profile');
});


Route::POST('/register', 'API\UserController@register');
Route::POST('/login', 'API\UserController@login');