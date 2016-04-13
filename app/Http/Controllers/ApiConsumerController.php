<?php

namespace App\Http\Controllers;

use App\ApiConsumer;
use App\Http\Requests;
use App\Utilities\JwTokenManager;
use App\Services\ApiConsumer\ApiConsumerWebService;
use App\Http\Requests\ApiConsumer\ApiConsumerRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerAccessRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerUpdateRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerResetKeyRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerActivationRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerReactivationRequest;
use App\Http\Requests\ApiConsumer\ApiConsumerRefreshTokenRequest;

class ApiConsumerController extends BaseController
{

    protected $apiConsumerWebService;

    /**
     * ApiConsumerController constructor.
     *
     * @param JwTokenManager $jwTokenManager
     * @param ApiConsumerWebService $apiConsumerWebService
     */
    public function __construct(JwTokenManager $jwTokenManager, ApiConsumerWebService $apiConsumerWebService)
    {
        $this->middleware('api.owner', ['only' => 'show', 'update', 'destroy']);
        $this->apiConsumerWebService = $apiConsumerWebService;
        parent::__construct($jwTokenManager);
    }

    /**
     * Display the landing page for the public API.
     *
     * @return $this
     */
    public function index()
    {
        $pageTitle = env('SITE_NAME') . ' Public API';
        $apiConsumer = $this->apiConsumerWebService->getLoggedInApiConsumer();

        return view('api_consumers.index-api-consumer')->with([
            'page_title'   => $pageTitle,
            'api_consumer' => $apiConsumer
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Create an API Account';

        return view('api_consumers.create-api-consumer')->with(['page_title' => $pageTitle]);
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

        return $this->apiConsumerWebService->getStarterTokenRoute($apiConsumer);
    }

    /**
     * Display the Api Token Activation page.
     *
     * @return $this
     */
    public function getActivate()
    {
        $pageTitle = 'Activate API Access Token';

        return view('api_consumers.activate-api-consumer')->with(['page_title' => $pageTitle]);
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

        return $this->apiConsumerWebService->getActivationRoute($apiConsumer);
    }

    /**
     * Display the Reactivation page for users who have had a failed activation attempt.
     *
     * @return $this
     */
    public function getReactivate()
    {
        $pageTitle = 'API Token Activation Error';

        return view('api_consumers.reactivate-api-consumer')->with(['page_title' => $pageTitle]);
    }

    /**
     * Determine which route to send a failed activation attempt through based on an email and redirect accordingly.
     *
     * @param ApiConsumerReactivationRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postReactivate(ApiConsumerReactivationRequest $request)
    {
        return $this->apiConsumerWebService->getReactivationRoute($request->get('email'));
    }

    /**
     * Display the specified ApiConsumer's Settings page.
     *
     * @param ApiConsumer $model
     * @return $this
     */
    public function show($model)
    {
        $apiConsumer = $this->apiGetRequest('api-consumer/' . $model->id);
        $pageTitle = 'API Account ' . $apiConsumer->id;

        return view('api_consumers.show-api-consumer')->with([
            'page_title'   => $pageTitle,
            'api_consumer' => $apiConsumer
        ]);
    }

    /**
     * Attempt to generate a new reset key and email it to the ApiConsumer, then redirect back with feedback.
     *
     * @param ApiConsumerResetKeyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postResetKey(ApiConsumerResetKeyRequest $request)
    {
        $apiConsumer = $this->apiPostRequest('api-consumer/reset-key', $request->all());

        return $this->apiConsumerWebService->getPublicResetKeyRoute($apiConsumer);
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

        return $this->apiConsumerWebService->getStarterTokenRoute($apiConsumer);
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
        $apiConsumer = $this->apiPutRequest('api-consumer/' . $model->id, $request->all());

        return $this->apiConsumerWebService->getUpdateRoute($apiConsumer);
    }

    /**
     * Attempt to delete an ApiConsumer from the DB, and return the appropriate JsonResponse on success or failure.
     *
     * @param ApiConsumer $model
     * @return mixed
     */
    public function destroy($model)
    {
        return $this->apiDeleteRequest('api-consumer/' . $model->id);
    }

    /**
     * Attempt to log an ApiConsumer in to the WEB APP, then redirect based on success or failure with appropriate
     * feedback and session variables.
     *
     * @param ApiConsumerAccessRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function accessWebApp(ApiConsumerAccessRequest $request)
    {
        $apiConsumer = $this->apiConsumerWebService->logInToWebApp($request->all());

        return $this->apiConsumerWebService->getWebAccessRoute($apiConsumer);
    }


    /**
     * If an ApiConsumer is logged in to the WEB APP, log them out, then redirect to the Public API landing page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout()
    {
        return $this->apiConsumerWebService->logOutOfWebApp();
    }
}
