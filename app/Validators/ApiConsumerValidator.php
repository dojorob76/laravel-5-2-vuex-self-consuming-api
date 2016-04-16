<?php

namespace App\Validators;

use App\ApiConsumer;
use App\Utilities\ApiTokenManager;

class ApiConsumerValidator
{

    private $apiTokenManager;

    public function __construct(ApiTokenManager $apiTokenManager)
    {
        $this->apiTokenManager = $apiTokenManager;
    }

    /**
     * Extends validator to check for unique email addresses, except in the case of ApiConsumers that have not yet
     * been activated.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function uniqueIfActive($attribute, $value, $parameters, $validator)
    {
        $aC = ApiConsumer::where('email', $value)->first();

        if ($aC instanceof ApiConsumer) {
            // If the ApiConsumer instance has a starter token, and is level 0, it is not active yet
            return $this->apiTokenManager->getTokenStatus($aC->api_token) == 'starter' && $aC->level == 0;
        }

        return true;
    }

    /**
     * Extends Validator to check that the ApiConsumer ID in the 'activate-api-consumer' form matches the ID appended
     * to the human-readable Starter Token.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function tokenId($attribute, $value, $parameters, $validator)
    {
        // Get the token info array from the token provided in the form
        $tokenInfo = $this->apiTokenManager->getTokenInfoArray($value);
        // Get the ApiConsumer ID that was provided in the form
        $id = $parameters[0];

        // If the ID in the form matches the ID in the token, return true, otherwise return false
        return $id == $tokenInfo['id'] ? true : false;
    }

    /**
     * Extends Validator to ensure that the starter token portion of the token provided in the 'activate-api-consumer'
     * form is the correct length.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function tokenSize($attribute, $value, $parameters, $validator)
    {
        // Get the token info array from the token provided in the form
        $tokenInfo = $this->apiTokenManager->getTokenInfoArray($value);

        // If the starter token portion of the provided token is the correct length, return true, else return false
        return $this->apiTokenManager->getTokenStatus($tokenInfo['starter_token']) == 'starter';
    }

    /**
     * Extends Validator to ensure that the starter token portion of the token provided in the 'activate-api-consumer'
     * form matches the starter token value stored on the ApiConsumer.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function tokenMatch($attribute, $value, $parameters, $validator)
    {
        // Get the ApiConsumer from the 'api_consumer_id' form field value
        $aC = ApiConsumer::find($value);
        // Get the token info array from the token provided in the form
        $tokenInfo = $this->apiTokenManager->getTokenInfoArray($parameters[0]);

        // If the ApiConsumer exists and their starter token matches the starter token portion of the token provided in
        // the form, return true, otherwise return false
        return $aC instanceof ApiConsumer ? $aC->api_token == $tokenInfo['starter_token'] : false;
    }

    /**
     * Extends Validator to ensure that the reset key provided in the 'refresh-token' form is the correct size.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function resetKeySize($attribute, $value, $parameters, $validator)
    {
        // If the reset key provided is the same size as a valid reset key, return true, otherwise return false
        return strlen($value) === strlen($this->apiTokenManager->generateResetKey());
    }

    /**
     * Extends Validator to ensure that the reset key provided in the 'refresh-token' form matches the reset key set
     * on the ApiConsumer.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validResetKey($attribute, $value, $parameters, $validator)
    {
        // Get the API Consumer from the email provided in the email form field
        $aC = ApiConsumer::where('email', $parameters[0])->first();

        // If the ApiConsumer exists and their reset key matches the reset key provided in the form, return true,
        // otherwise return false
        return $aC instanceof ApiConsumer ? $value == $aC->reset_key : false;
    }

    /**
     * Extends Validator to check the API Credentials provided in the 'api-consumer-web-access' form.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validApiCredentials($attribute, $value, $parameters, $validator)
    {
        // Get the API Consumer from the email provided in the email form field
        $aC = ApiConsumer::where('email', $parameters[0])->first();

        // If the ApiConsumer exists and the token provided passes the Hash check, return true, otherwise return false
        return $aC instanceof ApiConsumer ? \Hash::check($value, $aC->api_token) : false;
    }
}