<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Utilities\JwTokenManager;
use App\Utilities\AppAuthManager;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Requests\Authentication\UserLoginRequest;
use App\Http\Requests\Authentication\UserRegistrationRequest;

class AuthenticationController extends BaseController
{

    use ResetsPasswords;

    protected $appAuth;

    /**
     * The 'forgot password' view.
     *
     * @var string
     */
    protected $linkRequestView = 'authentication.forgot-password';

    /**
     * The 'reset password' view.
     *
     * @var string
     */
    protected $resetView = 'authentication.reset-password';

    /**
     * AuthenticationController constructor.
     *
     * @param JwTokenManager $jwTokenManager
     * @param AppAuthManager $appAuth
     */
    public function __construct(JwTokenManager $jwTokenManager, AppAuthManager $appAuth)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->appAuth = $appAuth;
        parent::__construct($jwTokenManager);
    }

    /**
     * Display the register page.
     *
     * @return $this
     */
    public function getRegister()
    {
        $this->appAuth->setRedirectRoute();

        $pageTitle = 'Register a New ' . env('SITE_NAME') . ' Account';

        return view('authentication.register')->with(['page_title' => $pageTitle]);
    }

    /**
     * Attempt to create a new User, log them in and set their JWT, then redirect with appropriate feedback.
     *
     * @param UserRegistrationRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRegister(UserRegistrationRequest $request)
    {
        $user = $this->apiPostRequest('user', $request->all());

        if (!json_decode($user)) {
            return $this->appAuth->setAuthFailureAction($user, $request);
        }

        return $this->appAuth->registerUser($user, $request);
    }

    /**
     * Display the log in page.
     *
     * @return $this
     */
    public function getLogin()
    {
        $this->appAuth->setRedirectRoute();

        $pageTitle = 'Log In to ' . env('SITE_NAME');

        return view('authentication.login')->with(['page_title' => $pageTitle]);
    }

    /**
     * Attempt to log a User in, set their JWT and redirect them.
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLogin(UserLoginRequest $request)
    {
        return $this->appAuth->login($request);
    }

    /**
     * Log a User out of the app, remove their JWT and redirect them.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout(Request $request)
    {
        return $this->appAuth->logoutUser($request);
    }
}