<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        |-------------------------------------------------------------------------------------------------------------
        | GLOBAL Custom Validation Rules
        |-------------------------------------------------------------------------------------------------------------
        */
        /**
         * Extends validator to ensure that the ID provided in a form field matches the ID in the URL Route.
         */
        Validator::extend('model_match', function ($attribute, $value, $parameters, $validator) {
            // If the value of the form field matches the model ID in the URL Route, return true, else return false
            return $value === $parameters[0];
        });

        Validator::extend('admin_only', function ($attribute, $value, $parameters, $validator) {
            // The Full URL has been passed through as the parameter
            $fullUrl = $parameters[0];
            // The admin subdomain name has been passed through as the 2nd parameter
            $admin = $parameters[1];
            // Remove the URL Protocol
            $trimmedUrl = ltrim($fullUrl, env('URL_PROTOCOL'));

            // If we are in the admin subdomain, pass, otherwise fail
            return substr($trimmedUrl, 0, strlen($admin)) == $admin;
        });

        /*
        |-------------------------------------------------------------------------------------------------------------
        | Custom Validation Rules specific to API CONSUMER forms (location: App\Validators\ApiConsumerValidator)
        |-------------------------------------------------------------------------------------------------------------
        */
        Validator::extend('unique_if_active', 'App\Validators\ApiConsumerValidator@uniqueIfActive');
        Validator::extend('token_id', 'App\Validators\ApiConsumerValidator@tokenId');
        Validator::extend('token_size', 'App\Validators\ApiConsumerValidator@tokenSize');
        Validator::extend('token_match', 'App\Validators\ApiConsumerValidator@tokenMatch');
        Validator::extend('reset_key_size', 'App\Validators\ApiConsumerValidator@resetKeySize');
        Validator::extend('valid_reset_key', 'App\Validators\ApiConsumerValidator@validResetKey');
        Validator::extend('valid_api_credentials', 'App\Validators\ApiConsumerValidator@validApiCredentials');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
