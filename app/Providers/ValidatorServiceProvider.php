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

        /*
        |-------------------------------------------------------------------------------------------------------------
        | Custom Validation Rules specific to API CONSUMER forms (location: App\Validators\ApiConsumerValidator)
        |-------------------------------------------------------------------------------------------------------------
        */
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
