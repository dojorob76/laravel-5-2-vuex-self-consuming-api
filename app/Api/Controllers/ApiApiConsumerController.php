<?php

namespace App\Api\Controllers;

use App\Http\Requests;
use App\Utilities\JwTokenManager;
use App\Http\Controllers\BaseController;
use App\Api\Transformers\ApiConsumerTransformer;
use App\Services\ApiConsumer\ApiConsumerService;
use App\Http\Requests\ApiConsumer\ApiConsumerRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerUpdateRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerResetKeyRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerActivationRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerReactivationRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerRefreshTokenRequest;

class ApiApiConsumerController extends BaseController
{

    protected $apiConsumerService;

    /**
     * ApiApiConsumerController constructor.
     *
     * @param JwTokenManager $jwTokenManager
     * @param ApiConsumerService $apiConsumerApiService
     */
    public function __construct(JwTokenManager $jwTokenManager, ApiConsumerService $apiConsumerApiService)
    {
        $this->apiConsumerService = $apiConsumerApiService;
        parent::__construct($jwTokenManager);
    }

    /**
     * Retrieve and return a list of all ApiConsumers.
     *
     * @return \Dingo\Api\Http\Response|void
     */
    public function index()
    {
        return $this->setApiResponse($this->apiConsumerService->getAllApiConsumers());
    }

    /**
     * Attempt to store a new API Consumer with starter token in the DB and return it or return a DingoAPI (void) error
     * response.
     *
     * @param ApiConsumerRequest $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function store(ApiConsumerRequest $request)
    {
        return $this->setApiResponse($this->apiConsumerService->setNewApiConsumer($request->get('email')));
    }

    /**
     * Attempt to activate an API Consumer access token and return the updated ApiConsumer or a DingoAPI (void) error
     * response.
     *
     * @param ApiConsumerActivationRequest $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function activate(ApiConsumerActivationRequest $request)
    {
        return $this->setApiResponse($this->apiConsumerService->activateValidApiConsumer($request->all()));
    }

    /**
     * Attempt to retrieve an ApiConsumer by the email provided in the reactivation form or return a DingoAPI (void)
     * error response.
     *
     * @param ApiConsumerReactivationRequest $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function reactivate(ApiConsumerReactivationRequest $request)
    {
        return $this->setApiResponse($this->apiConsumerService->reactivateApiConsumer($request->all()));
    }

    /**
     * Attempt to retrieve and return an ApiConsumer by their ID or return a DingoAPI (void) error response.
     *
     * @param  int $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function show($id)
    {
        return $this->setApiResponse($this->apiConsumerService->getApiConsumerById($id));
    }

    /**
     * Attempt to generate a new Reset Key, store it in the DB, and return the updated ApiConsumer or a DingoAPI
     * (void) error response.
     *
     * @param ApiConsumerResetKeyRequest $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function postResetKey(ApiConsumerResetKeyRequest $request)
    {
        return $this->setApiResponse($this->apiConsumerService->setApiConsumerResetKey($request->all()));
    }

    /**
     * Attempt to generate a new starter token, store it in the database, and return the updated ApiConsumer or a
     * DingoApi (void) error response.
     *
     * @param ApiConsumerRefreshTokenRequest $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function refreshToken(ApiConsumerRefreshTokenRequest $request)
    {
        return $this->setApiResponse($this->apiConsumerService->updateApiConsumerToken($request->all()));
    }

    /**
     * Attempt to update and return an existing ApiConsumer in the DB or return a DingoApi (void) error response.
     *
     * @param ApiConsumerUpdateRequest $request
     * @param int $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function update(ApiConsumerUpdateRequest $request, $id)
    {
        return $this->setApiResponse($this->apiConsumerService->updateExistingApiConsumer($id, $request->all()));
    }

    /**
     * Attempt to remove an ApiConsumer from the DB.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return $this->apiConsumerService->destroySingleApiConsumer($id);
    }

    /**
     * Set and return a DingoApi ApiConsumer item, collection, or (void) error Response.
     *
     * @param mixed $apiConsumer
     * @return \Dingo\Api\Http\Response|void
     */
    private function setApiResponse($apiConsumer)
    {
        return $this->getApiResponse($apiConsumer, new ApiConsumerTransformer);
    }
}
