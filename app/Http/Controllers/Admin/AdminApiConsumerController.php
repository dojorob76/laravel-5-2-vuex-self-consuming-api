<?php

namespace App\Http\Controllers\Admin;

use App\ApiConsumer;
use App\Http\Requests;
use App\Utilities\JwTokenManager;
use App\Http\Controllers\BaseController;
use App\Services\ApiConsumer\ApiConsumerWebService;
use App\Http\Requests\ApiConsumer\ApiConsumerRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerUpdateRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerResetKeyRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerActivationRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerReactivationRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerRefreshTokenRequest;

class AdminApiConsumerController extends BaseController
{

    protected $apiConsumerWebService;

    /**
     * AdminApiConsumerController constructor.
     *
     * @param JwTokenManager $jwTokenManager
     * @param ApiConsumerWebService $apiConsumerWebService
     */
    public function __construct(JwTokenManager $jwTokenManager, ApiConsumerWebService $apiConsumerWebService)
    {
        $this->apiConsumerWebService = $apiConsumerWebService;
        parent::__construct($jwTokenManager);
    }

    /**
     * Display the Admin Api Consumer Management main page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apiConsumers = $this->apiGetRequestWithJwt('api-consumer', 'v2');

        if(!json_decode($apiConsumers)){
            // The JWT validation did not pass - Admin needs to log in again
            return $this->getRedirectResponseForRequest('logout', 401);
        }

        $pageTitle = 'API Consumers Admin Management';

        return view('api_consumers.admin.admin-index-api-consumer')->with([
            'page_title'    => $pageTitle,
            'api_consumers' => $apiConsumers
        ]);
    }

    /**
     * Show the Admin form for creating a new ApiConsumer.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create New Api Consumer';

        return view('api_consumers.admin.admin-create-api-consumer')->with(['page_title' => $pageTitle]);
    }

    /**
     * Determine whether the ApiConsumer's starter token was successfully updated and redirect to the appropriate
     * page with feedback.
     *
     * @param ApiConsumerRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ApiConsumerRequest $request)
    {
        $apiConsumer = $this->apiPostRequest('api-consumer', $request->all());

        if (!json_decode($apiConsumer)) {
            return $this->apiConsumerWebService->starterTokenErrorResponse($request, $apiConsumer);
        }

        return $this->apiConsumerWebService->starterTokenSuccessResponse($request, $apiConsumer);
    }


    /**
     * Display the Admin API Access Token activation page.
     *
     * @return $this
     */
    public function getActivate()
    {
        $pageTitle = 'Activate API Access Token';

        return view('api_consumers.admin.admin-activate-api-consumer')->with(['page_title' => $pageTitle]);
    }

    /**
     * Attempt to activate an Api Access Token, then redirect with feedback according to whether or not activation was
     * successful.
     *
     * @param ApiConsumerActivationRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postActivate(ApiConsumerActivationRequest $request)
    {
        $apiConsumer = $this->apiPostRequest('api-consumer/activate', $request->all());

        if (!json_decode($apiConsumer)) {
            return $this->apiConsumerWebService->activationErrorResponse($request, $apiConsumer);
        }

        return $this->apiConsumerWebService->activationSuccessResponse($request, $apiConsumer);
    }

    /**
     * Display the reactivation page after a failed activation attempt.
     *
     * @return $this
     */
    public function getReactivate()
    {
        $pageTitle = 'API Token Activation Error';

        return view('api_consumers.admin.admin-reactivate-api-consumer')->with(['page_title' => $pageTitle]);
    }

    /**
     * Determine which route to send a failed activation attempt through based on an email and redirect accordingly.
     *
     * @param ApiConsumerReactivationRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postReactivate(ApiConsumerReactivationRequest $request)
    {
        $apiConsumer = $this->apiPostRequest('api-consumer/reactivate', $request->all());

        if (!json_decode($apiConsumer)) {
            return $this->apiConsumerWebService->reactivationNewApiConsumerResponse($request);
        }

        return $this->apiConsumerWebService->reactivationExistingApiConsumerResponse($request, $apiConsumer);
    }

    /**
     * Display the specified ApiConsumer's Settings Page - ADMIN version.
     *
     * @param ApiConsumer $model
     * @return $this
     */
    public function show($model)
    {
        $apiConsumer = $this->apiGetRequest('api-consumer/' . $model->id, 'v1', true);

        if(!json_decode($apiConsumer)){
            // Validation did not pass - Admin needs to log in again
            return $this->getRedirectResponseForRequest('logout', 401);
        }

        $pageTitle = 'API Consumer ' . $apiConsumer->id;

        return view('api_consumers.admin.admin-show-api-consumer')->with([
            'page_title'   => $pageTitle,
            'api_consumer' => $apiConsumer
        ]);
    }

    /**
     * Attempt to generate a new reset key, then redirect back with ADMIN-SPECIFIC info/feedback.
     *
     * @param ApiConsumerResetKeyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postResetKey(ApiConsumerResetKeyRequest $request)
    {
        $apiConsumer = $this->apiPostRequest('api-consumer/reset-key', $request->all(), 'v1', true);

        if (!json_decode($apiConsumer)) {
            return $this->apiConsumerWebService->resetKeyErrorResponse($request, $apiConsumer);
        }

        return $this->apiConsumerWebService->resetKeySuccessResponse($request, $apiConsumer);
    }

    /**
     * Attempt to generate a new starter token for an ApiConsumer, then redirect according to whether or not it was
     * successful with appropriate feedback and session variables.
     *
     * @param ApiConsumerRefreshTokenRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function refreshToken(ApiConsumerRefreshTokenRequest $request)
    {
        $apiConsumer = $this->apiPostRequest('api-consumer/refresh-token', $request->all());

        if (!json_decode($apiConsumer)) {
            return $this->apiConsumerWebService->starterTokenErrorResponse($request, $apiConsumer);
        }

        return $this->apiConsumerWebService->starterTokenSuccessResponse($request, $apiConsumer);
    }

    /**
     * Attempt to update an ApiConsumer, then redirect back with appropriate feedback.
     *
     * @param ApiConsumerUpdateRequest $request
     * @param ApiConsumer $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ApiConsumerUpdateRequest $request, $model)
    {
        $apiConsumer = $this->apiPutRequest('api-consumer/' . $model->id, $request->all(), 'v1', true);

        if (!json_decode($apiConsumer)) {
            return $this->apiConsumerWebService->updateErrorResponse($request, $apiConsumer);
        }

        return $this->apiConsumerWebService->updateSuccessResponse($request);
    }

    /**
     * Attempt to delete an ApiConsumer from the DB, and return the appropriate JsonResponse on success or failure.
     *
     * @param ApiConsumer $model
     * @return mixed
     */
    public function destroy($model)
    {
        $deleted = $this->apiDeleteRequest('api-consumer/' . $model->id, 'v1', true);

        return $this->apiConsumerWebService->getDeleteResponse($this->getRequestInstance(), $deleted);
    }
}
