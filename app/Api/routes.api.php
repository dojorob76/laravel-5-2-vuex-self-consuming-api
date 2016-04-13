<?php

/*
|--------------------------------------------------------------------------
| Dingo generated router for API Routes
|--------------------------------------------------------------------------
*/
$api = app('Dingo\Api\Routing\Router');
$dispatcher = app('Dingo\Api\Dispatcher');

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
*/
$api->version('v1', [
    // Because we are within the dingo router, we must include the name space again
    'namespace'  => 'App\Api\Controllers',
    'middleware' => 'api.access'
], function ($api) {
    // Api Consumers (Create New, Activate, Reset/Refresh)
    $api->post('api-consumer', 'ApiApiConsumerController@store')->name('api-consumer.post');
    $api->post('api-consumer/activate', 'ApiApiConsumerController@activate')->name('api-consumer/activate.post');
    $api->post('api-consumer/reset-key', 'ApiApiConsumerController@postResetKey')
        ->name('api-consumer/reset-key.post');
    $api->post('api-consumer/refresh-token', 'ApiApiConsumerController@refreshToken')
        ->name('api-consumer/refresh-token.post');

    // Only allow Admins or Api Consumers to view/edit their own token info
    $api->group(['middleware' => 'consumer.owner'], function ($api) {
        // Api Consumers (View Own, Update, Delete)
        $api->get('api-consumer/{id}', 'ApiApiConsumerController@show')->name('api-consumer/{id}.get');
        $api->put('api-consumer/{id}', 'ApiApiConsumerController@update')->name('api-consumer/{id}.put');
        $api->delete('api-consumer/{id}', 'ApiApiConsumerController@destroy')->name('api-consumer/{id}.delete');
    });
});

/*
|--------------------------------------------------------------------------
| API v2 Routes
|--------------------------------------------------------------------------
*/
$api->version('v2', [
    // Because we are within the dingo router, we must include the name space again
    'namespace'  => 'App\Api\Controllers',
    'middleware' => 'api.admin'
], function ($api) {
    // Api Consumers (GET (all))
    $api->get('api-consumer', 'ApiApiConsumerController@index')->name('api-consumer.get');
});