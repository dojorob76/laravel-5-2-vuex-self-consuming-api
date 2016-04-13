<?php

/*
|--------------------------------------------------------------------------
| ADMIN Subdomain Routes
|--------------------------------------------------------------------------
*/

// Api Consumer Routes
Route::get('admin-api-consumer/activate', 'AdminApiConsumerController@getActivate');
Route::post('admin-api-consumer/activate', 'AdminApiConsumerController@postActivate');
Route::get('admin-api-consumer/reactivate', 'AdminApiConsumerController@getReactivate');
Route::post('admin-api-consumer/reactivate', 'AdminApiConsumerController@postReactivate');
Route::post('admin-api-consumer/reset-key', 'AdminApiConsumerController@postResetKey');
Route::post('admin-api-consumer/refresh-token', 'AdminApiConsumerController@refreshToken');
Route::resource('admin-api-consumer', 'AdminApiConsumerController', ['except' => 'edit']);