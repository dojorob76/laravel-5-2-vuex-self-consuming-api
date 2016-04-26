<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
// App Registration, Login and Logout Routes
Route::get('authenticate', 'AuthenticationController@getAuthenticate');
Route::post('register', 'AuthenticationController@postRegister');
Route::post('login', 'AuthenticationController@postLogin');
Route::get('logout', 'AuthenticationController@getLogout');

// Password Reset Routes
Route::get('password/reset/{token?}', 'AuthenticationController@showResetForm');
Route::post('password/email', 'AuthenticationController@sendResetLinkEmail');
Route::post('password/reset', 'AuthenticationController@reset');

// JWT Verification Route
Route::get('verify-token', 'AuthenticationController@getTokenVeirfication');

/*
|--------------------------------------------------------------------------
| API CONSUMER Routes
|--------------------------------------------------------------------------
*/
Route::get('api-consumer/activate', 'ApiConsumerController@getActivate');
Route::post('api-consumer/activate', 'ApiConsumerController@postActivate');
Route::get('api-consumer/reactivate', 'ApiConsumerController@getReactivate');
Route::post('api-consumer/reactivate', 'ApiConsumerController@postReactivate');
Route::post('api-consumer/reset-key', 'ApiConsumerController@postResetKey');
Route::post('api-consumer/refresh-token', 'ApiConsumerController@refreshToken');
Route::post('api-consumer/access', 'ApiConsumerController@accessWebApp');
Route::get('api-consumer/logout', 'ApiConsumerController@getLogout');
Route::resource('api-consumer', 'ApiConsumerController', ['except' => 'edit']);