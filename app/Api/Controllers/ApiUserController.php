<?php

namespace App\Api\Controllers;

use App\Api\Transformers\UserTransformer;
use App\Http\Requests;
use App\Services\Users\UserService;
use Illuminate\Http\Request;
use App\Utilities\JwTokenManager;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Authentication\UserRegistrationRequest;

class ApiUserController extends BaseController
{

    protected $userService;

    /**
     * ApiUserController constructor.
     *
     * @param JwTokenManager $jwTokenManager
     * @param UserService $userService
     */
    public function __construct(JwTokenManager $jwTokenManager, UserService $userService)
    {
        $this->userService = $userService;
        parent::__construct($jwTokenManager);
    }

    /**
     * Attempt to retrieve and return a complete list of the resource or return a DingoAPI (void) error response.
     *
     * @return \Dingo\Api\Http\Response|void
     */
    public function index()
    {
        //
    }

    /**
     * Attempt to store a new User in the DB and return it, or return a DingoAPI (void) error response.
     *
     * @param UserRegistrationRequest $request
     * @return \Dingo\Api\Http\Response|void
     */
    public function store(UserRegistrationRequest $request)
    {
        return $this->setApiResponse($this->userService->createNewUser($request->all()));
    }

    /**
     * Attempt to retrieve and return a resource by its ID or return a DingoAPI (void) error response.
     *
     * @param  int $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function show($id)
    {
        //
    }

    /**
     * Attempt to update and return an existing resource in the DB or return a DingoApi (void) error response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Dingo\Api\Http\Response|void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Attempt to remove a resource from the DB (will return # of destroyed resources).
     *
     * @param  int $id
     * @return int
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Determine what kind of object we received from the Model Service and return the appropriate API Response.
     *
     * @param mixed $user
     * @return \Dingo\Api\Http\Response|void
     */
    private function setApiResponse($user)
    {
        return $this->getApiResponse($user, new UserTransformer);
    }
}
