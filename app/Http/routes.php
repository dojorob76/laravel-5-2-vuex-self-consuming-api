<?php

Route::get('/', function () {
    return view('welcome');
});

// Api Consumer Routes
Route::get('api-consumer/activate', 'ApiConsumerController@getActivate');
Route::post('api-consumer/activate', 'ApiConsumerController@postActivate');
Route::get('api-consumer/reactivate', 'ApiConsumerController@getReactivate');
Route::post('api-consumer/reactivate', 'ApiConsumerController@postReactivate');
Route::post('api-consumer/reset-key', 'ApiConsumerController@postResetKey');
Route::post('api-consumer/refresh-token', 'ApiConsumerController@refreshToken');
Route::post('api-consumer/access', 'ApiConsumerController@accessWebApp');
Route::get('api-consumer/logout', 'ApiConsumerController@getLogout');
Route::resource('api-consumer', 'ApiConsumerController', ['except' => 'edit']);


Route::get('test', function () {
    dd(session()->all());
});