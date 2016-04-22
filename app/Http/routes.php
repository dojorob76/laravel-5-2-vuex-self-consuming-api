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
Route::get('register', 'AuthenticationController@getRegister');
Route::post('register', 'AuthenticationController@postRegister');
Route::get('login', 'AuthenticationController@getLogin');
Route::post('login', 'AuthenticationController@postLogin');
Route::get('logout', 'AuthenticationController@getLogout');

// Password Reset Routes
Route::get('password/reset/{token?}', 'AuthenticationController@showResetForm');
Route::post('password/email', 'AuthenticationController@sendResetLinkEmail');
Route::post('password/reset', 'AuthenticationController@reset');

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


Route::get('test', function () {
    $request = request();

    $cookies = $request->header('cookie');
    $cookieParts = explode(' ', $cookies);

    $key = 'jwt=';

    $cookie = '';

    foreach ($cookieParts as $crumb) {
        if (substr($crumb, 0, strlen($key)) === $key) {
            $cookie .= trim(ltrim($crumb, $key));
            break;
        }
    }

    $payload = JWTAuth::getPayload($cookie);
    $claims = $payload->get('yourmom');

    return $claims ?: null;
});