<?php

namespace App\Services\ApiConsumer;

use App\ApiConsumer;
use App\Services\ModelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Utilities\ApiTokenManager;
use App\Repositories\ApiConsumer\ApiConsumerRepositoryInterface;

class ApiConsumerService extends ModelService
{

    protected $apiTokenManager;
    protected $apiConsumerRepo;
    protected $model;

    /**
     * ApiConsumerService constructor.
     *
     * @param ApiTokenManager $apiTokenManager
     * @param ApiConsumerRepositoryInterface $apiConsumerRepo
     * @param ApiConsumer $model
     */
    public function __construct(
        ApiTokenManager $apiTokenManager,
        ApiConsumerRepositoryInterface $apiConsumerRepo,
        ApiConsumer $model
    ) {
        $this->apiTokenManager = $apiTokenManager;
        $this->apiConsumerRepo = $apiConsumerRepo;
        parent::__construct($apiConsumerRepo, $model);
    }

    /**
     * Return a collection of all ApiConsumers or a JsonResponse error.
     *
     * @return Collection|JsonResponse
     */
    public function getAllApiConsumers()
    {
        return $this->getAllInstances();
    }

    /**
     * Attempt to find and return an ApiConsumer by their ID or return a JsonResponse error.
     *
     * @param int $id
     * @return ApiConsumer|JsonResponse
     */
    public function findApiConsumerById($id)
    {
        return $this->findInstanceById($id);
    }

    /**
     * Attempt to find and return an ApiConsumer using an Email Address or return a JsonResponse error.
     *
     * @param string $email
     * @return ApiConsumer|JsonResponse
     */
    public function findApiConsumerByEmail($email)
    {
        return $this->findInstanceByValue('email', $email, 'string');
    }

    /**
     * Create a new ApiConsumer in the database and return it, or return a JsonResponse error.
     *
     * @param array $data
     * @return ApiConsumer|JsonResponse
     */
    public function createNewApiConsumer($data)
    {
        return $this->createNewInstance($data);
    }

    /**
     * Attempt to update an existing ApiConsumer and return it or return a JsonResponse error.
     *
     * @param int $id
     * @param array $data
     * @return ApiConsumer|JsonResponse
     */
    public function updateExistingApiConsumer($id, $data)
    {
        return $this->updateExistingInstance($id, $data);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroySingleApiConsumer($id)
    {
        return $this->destroySingleInstance($id);
    }

    /**
     * @param array $ids
     * @return JsonResponse
     */
    public function destroyApiConsumers($ids)
    {
        return $this->destroyInstances($ids);
    }

    /**
     * Get a starter token (will not have user id appended) and attempt to create a new ApiConsumer with it, then return
     * the new ApiConsumer or a JsonResponse error.
     *
     * @param string $email
     * @return ApiConsumer|JsonResponse
     */
    public function setNewApiConsumer($email)
    {
        if (app()->environment() == 'local'){
            // Make sure we received the correct input type
            $this->miscastVar('string', $email, 'the email address of a new Api Consumer');
        }

        // If this email address is from an ApiConsumer who is not yet active, we will need to update instead of create
        $apiConsumer = $this->findApiConsumerByEmail($email);
        if ($apiConsumer instanceof ApiConsumer) {
            if ($apiConsumer->level == 0 && $this->apiTokenManager->getTokenStatus($apiConsumer->api_token) == 'starter') {
                // This is an ApiConsumer who has not activated their token, so send them through that process now
                return $this->updateApiConsumerToken(['email' => $email]);
            }

            // This is an existing, active ApiConsumer who never should have hit this method in the 1st place...
            return response()->json(['message' => 'This API Account is already active.'], 422);
        }
        // Otherwise, get fresh data for the new ApiConsumer
        $data = $this->getFreshApiConsumerData($email);
        // Attempt to create the new ApiConsumer with the data
        $newConsumer = $this->createNewApiConsumer($data);

        // Return the new ApiConsumer or a JsonResponse error on fail
        return $newConsumer;
    }

    /**
     * Attempt to update and return an ApiConsumer with a new, human-readable Starter Token or return a JsonResponse
     * error.
     *
     * @param array $data
     * @return ApiConsumer|JsonResponse
     */
    public function updateApiConsumerToken($data)
    {
        if (app()->environment() == 'local'){
            // Make sure we received the correct input type
            $this->miscastVar('array', $data, 'refresh Api Consumer token request data');
        }

        // If we received the correct input, attempt to retrieve the ApiConsumer
        $apiConsumer = $this->findApiConsumerByEmail($data['email']);

        // Make sure we have a valid ApiConsumer
        if ($apiConsumer instanceof ApiConsumer) {
            // Get fresh data for the ApiConsumer
            $newData = $this->getFreshApiConsumerData($apiConsumer->email);

            // Attempt to update and return the ApiConsumer or return a JsonResponse error
            return $this->updateExistingApiConsumer($apiConsumer->id, $newData);
        }

        // We do not have a valid ApiConsumer, so return JsonResponse error
        return $apiConsumer;
    }

    /**
     * Use the data from an ApiActivationRequest to update an ApiConsumer's starter token to an active/hashed token,
     * then return the ApiConsumer or a JsonResponse error.
     *
     * @param array $data
     * @return ApiConsumer|JsonResponse
     */
    public function activateValidApiConsumer($data)
    {
        if (app()->environment() == 'local'){
            // Make sure we received the correct input type
            $this->miscastVar('array', $data, 'Api Consumer activation request data');
        }

        // If we received the correct input type, attempt to retrieve the ApiConsumer
        $apiConsumer = $this->findApiConsumerById($data['api_consumer_id']);

        // Make sure we have a valid ApiConsumer
        if ($apiConsumer instanceof ApiConsumer) {
            // Set new ApiConsumers to level 1 - allow all others (refreshing) to retain their existing level
            $level = $apiConsumer->level != 0 ? $apiConsumer->level : 1;
            // Set the active token array for the update method - remove reset_key value if it exists
            $activeToken = [
                'api_token' => $this->apiTokenManager->generateActiveApiToken($data['starter_token']),
                'level'     => $level,
                'reset_key' => null
            ];

            // Attempt to update and return the ApiConsumer, or return JsonResponse error
            return $this->updateExistingApiConsumer($apiConsumer->id, $activeToken);
        }

        // We do not have a valid ApiConsumer, so return JsonResponse error
        return $apiConsumer;
    }

    /**
     * Generate a Reset Key for an ApiConsumer, then update and return the ApiConsumer or a JsonResponse error.
     *
     * @param array $data
     * @return ApiConsumer|JsonResponse
     */
    public function setApiConsumerResetKey($data)
    {
        if (app()->environment() == 'local'){
            // Make sure we received the correct input type
            $this->miscastVar('array', $data, 'Api Consumer reset key request data');
        }

        // If we received the correct input type, attempt to retrieve the ApiConsumer
        $apiConsumer = $this->findApiConsumerById($data['consumer_id']);

        // Make sure we have a valid ApiConsumer
        if ($apiConsumer instanceof ApiConsumer) {
            // Generate a Reset Key for the ApiConsumer
            $resetKey = $this->apiTokenManager->generateResetKey();

            // Return the ApiConsumer or the JsonResponse error.
            return $this->updateExistingApiConsumer($data['consumer_id'], ['reset_key' => $resetKey]);
        }

        // We do not have a valid ApiConsumer, so return JsonResponse error
        return $apiConsumer;
    }

    /**
     * Generate and return a data array for a new ApiConsumer or a token refresh.
     *
     * @param string $email
     * @return array
     */
    private function getFreshApiConsumerData($email)
    {
        // Get an (invalid) starter token to create or update the new Consumer with
        $token = $this->apiTokenManager->generateApiTokenStarter();

        $data = ['email' => $email, 'api_token' => $token];

        return $data;
    }
}