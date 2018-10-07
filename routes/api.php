<?php

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/profile' , 'API\UserController@profile');
    Route::get('/feed' , 'SecretController@index');
    Route::post('/secret/add' , 'SecretController@store');
    Route::post('/secret/comment/add' , 'CommentController@storeComment');
    Route::post('/secret/mark-as-spam' , 'SecretController@markAsSpam');
    Route::GET('/stories' , 'StoryController@list');
});


Route::POST('/register', 'API\UserController@register');
Route::POST('/login', 'API\UserController@login');

