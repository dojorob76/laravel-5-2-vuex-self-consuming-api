<?php

namespace App\Services\ApiConsumer;

use App\ApiConsumer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Jobs\SendApiResetKeyEmail;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;

class ApiConsumerWebService extends ApiConsumerService
{

    /**
     * If we have an ApiConsumer on the WEB APP, return it, otherwise return false.
     *
     * @return ApiConsumer|bool
     */
    public function getLoggedInApiConsumer()
    {
        // Check for the WebAccessToken in the ApiConsumer's session
        if (session()->has('api_consumer_token')) {
            // Get the ApiConsumer from the token
            $apiConsumer = $this->apiTokenManager->getApiConsumerFromToken(session('api_consumer_token'));
            if (!$apiConsumer || !$this->apiTokenManager->verifyApiToken(session('api_consumer_token'))) {
                // Remove any possible invalid WebTokens
                $this->apiTokenManager->removeWebAccessToken();
            }

            return $apiConsumer;
        }

        // If the WebAccessToken is not in the session, the ApiConsumer is not logged in
        return false;
    }

    /**
     * Log an APIConsumer out of the WEB APP and redirect them to the Public API Index page.
     *
     * @return RedirectResponse|Redirector
     */
    public function logOutOfWebApp()
    {
        // Remove the WebAccessToken in the ApiConsumer's session
        $this->apiTokenManager->removeWebAccessToken();
        // Generate a successful log out message for the ApiConsumer
        flasher()->vueSuccessTimed('You are now logged out of your API Account.', 'Goodbye');

        // Redirect to the ApiConsumer index page
        return $this->getRedirectResponseForRequest('api-consumer', 200);
    }

    /**
     * Send the appropriate session variables and feedback through the session and redirect to the ApiConsumer's
     * Settings page.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function getLoginResponse($request)
    {
        // If the request passed validation, the Log In was successful, so set the ApiConsumer and token variables
        $token = $request->get('api_token');
        $apiConsumer = $this->apiTokenManager->getApiConsumerFromToken($token);
        // Add the validWebAccessToken to the ApiConsumer's session
        $this->apiTokenManager->setValidWebAccessToken($token);
        // Display a success message to the ApiConsumer
        flasher()->vueSuccessTimed('You are now logged in to your API Account.', 'Welcome!');

        // Redirect to the ApiConsumer's Settings Page
        return $this->getRedirectResponseForRequest('api-consumer/' . $apiConsumer->id, 200, $request);
    }

    /**
     * @param Request $request
     * @param ApiConsumer $apiConsumer
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function starterTokenSuccessResponse(Request $request, ApiConsumer $apiConsumer)
    {
        // Get a valid (human-readable) API Access Token to display ONCE for the API Consumer
        $accessToken = $this->apiTokenManager->generateValidApiToken($apiConsumer);

        // Add the access token and the ApiConsumer's ID to the session for the activation form
        session()->flash('access_token', $accessToken);
        session()->flash('access_id', $apiConsumer->id);

        // Display an instructional message to the ApiConsumer
        $message = 'Your new API Access Token is not yet active. Please record and activate it now.';
        flasher()->vueInfo($message, 'Step One Complete');

        // If we are not in the admin subdomain, add the PreWebAccess token to the ApiConsumer's session
        if ($this->getSubdomain($request) != 'admin') {
            $this->apiTokenManager->setPreWebAccessToken($accessToken);
        }

        // Redirect the main/sub domain appropriate activate page
        return $this->getRedirectResponseForRequest('api-consumer/activate', 200, $request);
    }

    /**
     * @param Request $request
     * @param ApiConsumer $apiConsumer
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function activationSuccessResponse($request, $apiConsumer)
    {
        // Display a success message to the ApiConsumer
        flasher()->vueSuccessDismiss('Your New API Access Token is now active!');

        // If we are not in the admin subdomain, add the validatable WebAccessToken to the ApiConsumer's session
        if ($this->getSubdomain($request) != 'admin') {
            $this->apiTokenManager->setValidWebAccessToken();
        }

        // Redirect to the main/sub domain appropriate API Consumer Management page
        return $this->getRedirectResponseForRequest('api-consumer/' . $apiConsumer->id, 200, $request);
    }

    /**
     * @param Request $request
     * @param ApiConsumer $apiConsumer
     * @return JsonResponse|RedirectResponse
     */
    public function resetKeySuccessResponse(Request $request, ApiConsumer $apiConsumer)
    {
        if ($this->getSubdomain($request) == 'admin') {
            // Add the reset key to the session to display on the page
            flasher()->vueInfo($apiConsumer->reset_key, 'Reset Key');
        } else {
            // Queue up an email to the ApiConsumer with their reset key
            $job = (new SendApiResetKeyEmail($apiConsumer));
            $this->dispatch($job);

            // Display a success message to the ApiConsumer
            $msg = 'An email containing your reset key has been sent to ' . $apiConsumer->email . '.';
            flasher()->vueSuccessDismiss($msg);
        }

        return $this->getReloadResponseForRequest(200, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function updateSuccessResponse(Request $request)
    {
        // Display a success message
        flasher()->vueSuccessTimed('The settings have been successfully updated.');

        return $this->getReloadResponseForRequest(200, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function reactivationNewApiConsumerResponse(Request $request){
        $msg = 'This email address is not associated with an existing API Account. Create a new account now.';
        flasher()->vueInfo($msg);
        // Flash the email address to the session to be used in the create form
        session()->flash('email_address', $request->get('email'));
        // Redirect to the main/sub domain appropriate create page
        return $this->getRedirectResponseForRequest('api-consumer/create', 202, $request);
    }

    /**
     * @param Request $request
     * @param ApiConsumer $apiConsumer
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function reactivationExistingApiConsumerResponse(Request $request, ApiConsumer $apiConsumer)
    {
        if ($this->apiTokenManager->getTokenStatus($apiConsumer->api_token) == 'starter') {
            // The ApiConsumer currently has a starter token, so send them through the starter token route
            return $this->starterTokenSuccessResponse($request, $apiConsumer);
        }
        // The ApiConsumer with this email address currently has an active token, so display that message
        $msg = 'The API Access Token for this account is already active. Please log in to refresh it.';
        flasher()->vueError($msg);

        // Redirect to the main/sub domain appropriate index page
        return $this->getRedirectResponseForRequest('api-consumer', 202, $request);
    }

    /**
     * @param Request $request
     * @param JsonResponse $jsonError
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function starterTokenErrorResponse(Request $request, JsonResponse $jsonError)
    {
        // Display feedback to the user
        flasher()->vueError('Whoops! Something went wrong. Please try again.');

        // Redirect to the main/sub domain appropriate reactivate page
        return $this->getRedirectResponseForRequest('api-consumer/reactivate', $jsonError->getStatusCode(), $request);
    }

    /**
     * @param Request $request
     * @param JsonResponse $jsonError
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function activationErrorResponse($request, $jsonError)
    {
        // Display an error message to the ApiConsumer
        flasher()->vueError('Activation was unsuccessful. Please try again.');

        // Redirect to the main/sub domain appropriate reactivation page
        return $this->getRedirectResponseForRequest('api-consumer/reactivate', $jsonError->getStatusCode(), $request);
    }

    /**
     * @param Request $request
     * @param JsonResponse $jsonError
     * @return JsonResponse|RedirectResponse
     */
    public function resetKeyErrorResponse(Request $request, JsonResponse $jsonError)
    {
        $info = $this->getJsonInfoArray($jsonError);

        if ($this->getSubdomain($request) == 'admin') {
            // Display specific error details to site administrators
            $message = 'Reset Key was not generated: ' . $info['message'];
            $title = $info['status'] . ' error';
        } else {
            // Display generic 'Oops!' to standard users
            $message = 'We were unable to process your request. Please try again.';
            $title = 'Whoops!';
        }

        flasher()->vueErrorDismiss($message, $title);

        return $this->getReloadResponseForRequest($info['status'], $request);
    }

    /**
     * @param Request $request
     * @param JsonResponse $jsonError
     * @return JsonResponse|RedirectResponse
     */
    public function updateErrorResponse(Request $request, JsonResponse $jsonError)
    {
        // Display an error message
        flasher()->vueErrorDismiss('The update was unsuccessful. Please try again.');

        return $this->getReloadResponseForRequest($jsonError->getStatusCode(), $request);
    }

    /**
     * @param Request $request
     * @param array $deleted - (The DingoAPI will convert JsonResponse to array)
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function getDeleteResponse($request, $deleted)
    {
        if ($deleted['status'] === 200) {
            // The deletion was successful, so redirect to the main/sub domain appropriate index page
            $path = 'api-consumer';
            // If we are not in the admin subdomain, remove the WebAccessToken from the ApiConsumer's session
            if ($this->getSubdomain() != 'admin') {
                $this->apiTokenManager->removeWebAccessToken();
            }
            // If this is not an AJAX delete, display feedback to the user
            if (!$request->ajax() && !$request->wantsJson()) {
                flasher()->vueSuccessTimed('The API Account has been deleted.');
            }

            return $this->getRedirectResponseForRequest($path, 200, $request);
        }
        // If this is not an AJAX delete, display feedback to the user
        if (!$request->ajax() && !$request->wantsJson()) {
            flasher()->vueErrorDismiss('The API Account could not be deleted.');
        }

        // The deletion was unsuccessful, so reload the current page
        return $this->getReloadResponseForRequest($deleted['status'], $request);
    }
}