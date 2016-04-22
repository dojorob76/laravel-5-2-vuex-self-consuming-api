<?php

namespace App\Utilities;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use App\Services\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Requests\Authentication\UserLoginRequest;
use App\Http\Requests\Authentication\UserRegistrationRequest;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AppAuthManager
{

    use AuthenticatesAndRegistersUsers, ThrottlesLogins, RequestResponseUtilityTrait;

    protected $jwTokenManager;
    protected $userService;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct(JwTokenManager $jwTokenManager, UserService $userService)
    {
        $this->jwTokenManager = $jwTokenManager;
        $this->userService = $userService;
    }

    /**
     * Perform all necessary registration tasks, log the new User in, set their JWT, and redirect them with feedback.
     *
     * @param User $user
     * @param UserRegistrationRequest $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function registerUser(User $user, UserRegistrationRequest $request)
    {
        // TODO: Any necessary custom registration actions (dispatch welcome email job, etc.)

        // Assign the Bouncer 'member' role to the User
        $user->assign('member');

        // Log the User in to the app
        Auth::login($user);

        // Display registration success feedback
        flasher()->bsSuccessDismiss('Welcome to ' . env('SITE_NAME') . '! Your new account has been created.');

        // Attempt to set a JWT on the User, and redirect with feedback accordingly
        return $this->setJwtOnUser($user, $request);
    }

    /**
     * Log a User out of the app, remove the JWT from their session and redirect to home page.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function logoutUser(Request $request)
    {
        // Log the User out of the app
        if (Auth::check()) {
            Auth::logout();
        }

        // Display log out feedback
        flasher()->bsInfoDismiss('Thank you for visiting ' . env('SITE_NAME') . '. You are now logged out.', 'Goodbye');

        // Remove the JWT from the User session on redirect
        $cookie = $this->jwTokenManager->setJwtCookieExpired();

        return $this->getRedirectResponseForRequest('/', 200, $request, null, $cookie);
    }

    /**
     * Store the path to the page that the User was previously on before they hit the auth routes for use after post
     * methods. Defaults back to redirect path.
     */
    public function setRedirectRoute()
    {
        if (!session()->has('auth_redirect')) {
            $intended = null;
            if (session()->has('url') && array_key_exists('intended', session('url'))) {
                $url = session('url');
                $intended = $url['intended'];
            }
            $initialRoute = $intended == null ? session()->previousUrl() : $intended;
            $routeParts = explode('/', $initialRoute);

            if (!in_array(array_pop($routeParts), $this->authRoutes)) {
                session()->put('auth_redirect', $initialRoute);
            } else {
                session()->put('auth_redirect', $this->redirectPath());
            }
        }
    }

    /**
     * Do this if the authentication process fails at any point.
     *
     * @param JsonResponse $json
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function setAuthFailureAction($json, $request)
    {
        // If the User has been logged in already, log them out and remove any success feedback
        if (Auth::check()) {
            Auth::logout();

            if (session()->has('bs_flash')) {
                session()->pull('bs_flash');
            }

            if (session()->has('bs_dismiss')) {
                session()->pull('bs_dismiss');
            }
        }

        // Get the info from the JsonResponse error
        $info = $this->getJsonInfoArray($json);

        // Reload the page with the appropriate error feedback
        return $this->getReloadResponseForRequest($info['status'], $request, ['message' => $info['message']]);
    }

    /**
     * OVERRIDE this 'AuthenticatesUsers' Trait method to simply return null, as our validation has already been handled
     * by the UserLoginFormRequest.
     * ORIGINAL: \Illuminate\Foundation\Auth\AuthenticatesUsers@validateLogin
     *
     * @param  $request
     * @return void
     */
    protected function validateLogin($request)
    {
        return null;
    }

    /**
     * OVERRIDE this 'AuthenticatesUsers' Trait method to set a JWT on the User after successful log in.
     * ORIGINAL: \Illuminate\Foundation\Auth\AuthenticatesUsers@handleUserWasAuthenticated
     *
     * @param UserLoginRequest $request
     * @param $throttles
     * @return JsonResponse|RedirectResponse|Redirector
     */
    protected function handleUserWasAuthenticated(UserLoginRequest $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }

        if (method_exists($this, 'authenticated')) {
            return $this->authenticated($request, Auth::guard($this->getGuard())->user());
        }

        // Display Log in success feedback
        flasher()->bsSuccessDismiss('Welcome back, ' . Auth::user()->name . '! You are now logged in.');

        // Set the variable to update the User's 'token_key' column with
        $tokenKey = $request->get('token_key');

        // Attempt to update the User's token key, set a JWT, and redirect or reload accordingly
        return $this->setJwtOnUser($this->userService->updateUserTokenKey(Auth::user()->id, $tokenKey), $request);
    }

    /**
     * OVERRIDE this 'AuthenticatesUsers' Trait method to send custom-formatted response.
     * ORIGINAL: \Illuminate\Foundation\Auth\AuthenticatesUsers@sendFailedLoginResponse
     *
     * @param UserLoginRequest $request
     * @return JsonResponse|RedirectResponse
     */
    protected function sendFailedLoginResponse(UserLoginRequest $request)
    {
        flasher()->bsError($this->getFailedLoginMessage());

        return $this->getFailedAuthResponse($request);
    }

    /**
     * OVERRIDE this 'AuthenticatesUsers' Trait method to send custom-formatted response.
     * ORIGINAL: \Illuminate\Foundation\Auth\AuthenticatesUser@sendLockoutResponse
     *
     * @param UserLoginRequest $request
     * @return JsonResponse|RedirectResponse
     */
    protected function sendLockoutResponse(UserLoginRequest $request)
    {
        $seconds = $this->secondsRemainingOnLockout($request);

        flasher()->bsError($this->getLockoutErrorMessage($seconds));

        return $this->getFailedAuthResponse($request);
    }

    /**
     * Build and return the custom-formatted response for login failure/throttle lockout to maintain app consistency.
     *
     * @param UserLoginRequest $request
     * @return JsonResponse|RedirectResponse
     */
    private function getFailedAuthResponse(UserLoginRequest $request)
    {
        $formInput = ['email' => $request->get('email'), 'remember' => $request->get('remember')];
        session()->flash('_old_input', $formInput);

        return $this->getReloadResponseForRequest(422, $request);
    }

    /**
     * Attempt to set a JWT on the User and store it in their session, then redirect or reload with appropriate feedback
     * according to success or failure.
     *
     * @param User|JsonResponse $user
     * @param \Illuminate\Http\Request|null $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    private function setJwtOnUser($user, $request = null)
    {
        // Make sure we were passed a valid User
        if (!$user instanceof User) {
            return $this->setAuthFailureAction($user, $this->getRequestInstance($request));
        }

        // Generate a JWT for the User
        $jwt = $this->jwTokenManager->getJwtFromUser($user);

        // Make sure the JWT generation was successful
        if ($jwt instanceof JsonResponse) {
            return $this->setAuthFailureAction($jwt, $this->getRequestInstance($request));
        }

        // Redirect the User to the appropriate path with the JWT in the cookies
        $redirect = session()->pull('auth_redirect');
        $jwtMsg = ['jwtoken' => $jwt];
        $cookie = $this->jwTokenManager->setJwtCookieFresh($jwt);

        return $this->getRedirectResponseForRequest($redirect, 200, $request, $jwtMsg, $cookie);
    }
}