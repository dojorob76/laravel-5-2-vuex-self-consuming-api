<?php

namespace App\Services\ApiConsumer;

use App\ApiConsumer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use App\Utilities\ApiTokenManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\ApiConsumer\ApiConsumerRepositoryInterface;

class ApiConsumerService
{

    protected $apiTokenManager;
    protected $apiConsumerRepo;

    /**
     * ApiConsumerApiService constructor.
     *
     * @param ApiTokenManager $apiTokenManager
     * @param ApiConsumerRepositoryInterface $apiConsumerRepo
     */
    public function __construct(ApiTokenManager $apiTokenManager, ApiConsumerRepositoryInterface $apiConsumerRepo)
    {
        $this->apiTokenManager = $apiTokenManager;
        $this->apiConsumerRepo = $apiConsumerRepo;
    }

    /**
     * Return a collection of all ApiConsumers or a JsonResponse error.
     *
     * @return Collection|JsonResponse
     */
    public function getAllApiConsumers()
    {
        // Attempt to retrieve the ApiConsumers
        try {
            $apiConsumers = $this->apiConsumerRepo->getAll();
        } catch (\Exception $e) {
            // The ApiConsumers could not be retrieved, so return JsonResponse error
            return response()->json(['message' => $e->getMessage()], setStatus($e, 404));
        }

        // Return all ApiConsumers
        return $apiConsumers;
    }

    /**
     * Attempt to find and return an ApiConsumer by their ID or return a JsonResponse error.
     *
     * @param int $id
     * @return ApiConsumer|JsonResponse
     */
    public function getApiConsumerById($id)
    {
        // Make sure we received the correct input type
        miscastVar('intnum', $id, 'The ID of an API Consumer');

        // If we received the correct input, attempt to find the ApiConsumer
        try {
            $apiConsumer = $this->apiConsumerRepo->findById($id);
        } catch (ModelNotFoundException $e) {
            // The ApiConsumer does not exist, so return the JsonRepsonse error
            return response()->json(['message' => $e->getMessage()], setStatus($e, 404));
        }

        // Return the ApiConsumer
        return $apiConsumer;
    }

    /**
     * Attempt to find and return an ApiConsumer using an Email Address or return a JsonResponse error.
     *
     * @param string $email
     * @return ApiConsumer|JsonResponse
     */
    public function getApiConsumerByEmail($email)
    {
        // Make sure we received the correct input type
        miscastVar('string', $email, 'The email address of an API Consumer');

        // If we received the correct input, attempt to find the ApiConsumer
        try {
            $apiConsumer = $this->apiConsumerRepo->findByEmail($email);
        } catch (ModelNotFoundException $e) {
            // The ApiConsumer does not exist, so return the JsonRepsonse error
            return response()->json(['message' => $e->getMessage()], setStatus($e, 404));
        }

        // Return the ApiConsumer
        return $apiConsumer;
    }

    /**
     * Create a new ApiConsumer in the database and return it, or return a JsonResponse error.
     *
     * @param array $data
     * @return ApiConsumer|JsonResponse
     */
    public function createNewApiConsumer($data)
    {
        // Make sure we received the correct input type
        miscastVar('array', $data, 'create Api Consumer request data');

        // If we received the correct input, attempt to create the new ApiConsumer
        try {
            $newConsumer = $this->apiConsumerRepo->createNew($data);
        } catch (\Exception $e) {
            // The ApiConsumer could not be created, so return JsonResponse error
            return response()->json(['message' => $e->getMessage()], setStatus($e, 422));
        }

        // Return the new ApiConsumer
        return $newConsumer;
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
        // Make sure we received the correct input type for the ID
        miscastVar('intnum', $id, 'The ID of the API Consumer to update');
        // Make sure we received the correct input type for the Data
        miscastVar('array', $data, 'update API Consumer request data');

        // If we received the correct input, attempt to update the ApiConsumer
        try {
            $updatedConsumer = $this->apiConsumerRepo->updateExisting($id, $data);
        } catch (\Exception $e) {
            // The ApiConsumer could not be updated, so return JsonResponse error
            return response()->json(['message' => $e->getMessage()], setStatus($e, 422));
        }

        // Return the updated ApiConsumer
        return $updatedConsumer;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroySingleApiConsumer($id)
    {
        // Make sure we received the correct input type
        miscastVar('intnum', $id, 'The ID of an API Consumer to delete');

        // If we received the correct input, attempt to find the ApiConsumer
        $apiConsumer = $this->getApiConsumerById($id);

        // Make sure we are dealing with a valid ApiConsumer
        if ($apiConsumer instanceof ApiConsumer) {
            // If the ApiConsumer exists, attempt to delete them
            return $this->destroyApiConsumers([$id]);
        }

        // The API Consumer could not be found, so return JsonResponse error
        return $apiConsumer;
    }

    /**
     * @param array $ids
     * @return JsonResponse
     */
    public function destroyApiConsumers($ids)
    {
        // Make sure we received the correct input type
        miscastVar('array', $ids, 'The IDs of the API Consumers to be deleted');

        // If we received the correct input, attempt to delete the ApiConsumer(s)
        try {
            $response = $this->apiConsumerRepo->deleteItems($ids);
        } catch (\Exception $e) {
            // The ApiConsumer(s) could not be deleted, so return JsonResponse error
            return response()->json(['message' => $e->getMessage()], setStatus($e, 422));
        }
        // Set success/failure responses
        $sm = count($ids) === 1 ? 'Consumer has' : 'Consumers have';
        $fm = count($ids) === 1 ? 'Consumer was not' : 'Consumers have not been';
        $success = response()->json(['message' => $response . ' API ' . $sm . ' been deleted.'], 200);
        $fail = response()->json(['message' => 'The API ' . $fm . ' deleted.'], 422);

        return $response === 0 ? $fail : $success;
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
        // Make sure we received the correct input type
        miscastVar('string', $email, 'the email address of a new Api Consumer');

        // If this email address is from an ApiConsumer who is not yet active, we will need to update instead of create
        $apiConsumer = $this->getApiConsumerByEmail($email);
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
        // Make sure we received the correct input type
        miscastVar('array', $data, 'refresh Api Consumer token request data');

        // If we received the correct input, attempt to retrieve the ApiConsumer
        $apiConsumer = $this->getApiConsumerByEmail($data['email']);

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
        // Make sure we received the correct input type
        miscastVar('array', $data, 'Api Consumer activation request data');

        // If we received the correct input type, attempt to retrieve the ApiConsumer
        $apiConsumer = $this->getApiConsumerById($data['api_consumer_id']);

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
     * Attempt to retrieve and return an existing ApiConsumer by the email address provided in the reactivation form
     * or return JsonResponse error.
     *
     * @param array $data
     * @return ApiConsumer|JsonResponse
     */
    public function reactivateApiConsumer($data)
    {
        // Make sure we received the correct input type
        miscastVar('array', $data, 'Api Consumer reactivation request data');

        $apiConsumer = $this->getApiConsumerByEmail($data['email']);

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
        // Make sure we received the correct input type
        miscastVar('array', $data, 'Api Consumer reset key request data');

        // If we received the correct input type, attempt to retrieve the ApiConsumer
        $apiConsumer = $this->getApiConsumerById($data['consumer_id']);

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