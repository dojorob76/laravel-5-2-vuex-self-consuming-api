<?php

namespace App\Services\ApiConsumer;

use App\ApiConsumer;
use Illuminate\Http\Request;
use App\Services\WebServiceTrait;
use Illuminate\Http\JsonResponse;
use App\Jobs\SendApiResetKeyEmail;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ApiConsumerWebService extends ApiConsumerService
{

    use DispatchesJobs, WebServiceTrait;

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
        flasher()->bsSuccessDismiss('You are now logged out of your API Account.', 'Goodbye');

        // Redirect to the ApiConsumer index page
        return redirect(action('ApiConsumerController@index'));
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
        // Send a success message through the session to display to the ApiConsumer
        flasher()->bsSuccessDismiss('You are now logged in to your API Account.', 'Welcome!');
        // Redirect to the ApiConsumer's Settings Page
        $path = action('ApiConsumerController@show', $apiConsumer->id);

        return $this->getRedirectResponseForRequest($request, $path, 200);
    }

    /**
     * Determine whether a starter token was successfully updated, then respond with the appropriate redirect route
     * and feedback based on what type of request was received.
     *
     * @param Request $request
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function getStarterTokenResponse($request, $apiConsumer)
    {
        // If an ApiConsumer was returned, the starter token was successfully updated
        if ($apiConsumer instanceof ApiConsumer) {
            // Get a valid (human-readable) API Access Token to display ONCE for the API Consumer
            $accessToken = $this->apiTokenManager->generateValidApiToken($apiConsumer);

            // Add the access token and the ApiConsumer's ID to the session for the activation form
            session()->flash('access_token', $accessToken);
            session()->flash('access_id', $apiConsumer->id);

            // Display an instructional message to the ApiConsumer
            flasher()->bsInfo('Your new API Access Token has been created, but is not yet active. Please record and activate it now.', 'Step One Complete');

            // If we are not in the admin subdomain, add the PreWebAccess token to the ApiConsumer's session
            if (getSubdomain() != 'admin') {
                $this->apiTokenManager->setPreWebAccessToken($accessToken);
            }

            // Determine whether we are in a subdomain and set the correct activation path accordingly
            $path = $this->getPathForSubdomain('api-consumer/activate', $request);
            $status = 200;
        } else {
            // The starter token was not successfully updated, so return an error message to the ApiConsumer
            flasher()->bsError('Whoops! Something went wrong. Please try again.');
            // Determine whether we are in a subdomain and set the correct reactivation path accordingly
            $path = $this->getPathForSubdomain('api-consumer/reactivate', $request);
            $status = $apiConsumer->getStatusCode();
        }

        // Return appropriate session data/feedback and redirect
        return $this->getRedirectResponseForRequest($request, '/' . $path, $status);
    }

    /**
     * Determine whether API Access Token activation was successful, then respond with the appropriate redirect route
     * and feedback according to the type of request that was received.
     *
     * @param Request $request
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function getActivationResponse($request, $apiConsumer)
    {
        // If an ApiConsumer was returned, activation was successful
        if ($apiConsumer instanceof ApiConsumer) {
            // Return a success message to the ApiConsumer
            flasher()->bsSuccessDismiss('Your New API Access Token is now active!');
            // If we are not in the admin subdomain, add the validatable WebAccessToken to the ApiConsumer's session
            if (getSubdomain() != 'admin') {
                $this->apiTokenManager->setValidWebAccessToken();
            }
            // Determine whether we are in a subdomain and set the correct API Consumer Management page path accordingly
            $path = $this->getPathForSubdomain('api-consumer/' . $apiConsumer->id, $request);
            $status = 200;
        } else {
            // Activation was not successful, so return an error message to the ApiConsumer
            flasher()->bsError('Activation was unsuccessful. Please try again.');
            // Determine whether we are in a subdomain and set the correct reactivation path accordingly
            $path = $this->getPathForSubdomain('api-consumer/reactivate', $request);
            $status = $apiConsumer->getStatusCode();
        }

        // Return appropriate session data/feedback and redirect
        return $this->getRedirectResponseForRequest($request, '/' . $path, $status);
    }

    /**
     * Determine which route to send a reactivation request through based on the email provided, then redirect to
     * that page with corresponding session variables and feedback.
     *
     * @param Request $request
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function getReactivationResponse($request, $apiConsumer)
    {
        // Determine whether the ApiConsumer already exists
        if ($apiConsumer instanceof ApiConsumer) {
            // We have an ApiConsumer, let's find out if they currently have a starter token
            if ($this->apiTokenManager->getTokenStatus($apiConsumer->api_token) == 'starter') {
                // The ApiConsumer currently has a starter token, so send them through the starter token route
                return $this->getStarterTokenResponse($request, $apiConsumer);
            }
            // The ApiConsumer with this email address currently has an active token, so display that message
            flasher()->bsErrorDismiss('The API Access Token associated with this email address is already active. Please log in now if you would like to refresh it.');
            // Determine whether we are in a subdomain and set the correct API Consumer Management page path accordingly
            $path = $this->getPathForSubdomain('api-consumer', $request);
        } else {
            // The email address is not associated with an existing ApiConsumer, so display that message
            flasher()->bsInfo('Our records indicate that this email address does not currently have an API Access
            Token associated with it. Please generate a new token now.');
            // Flash the email address to the session to be used in the create form
            session()->flash('email_address', $request->get('email'));
            // Determine whether we are in a subdomain and set the correct create path accordingly
            $path = $this->getPathForSubdomain('api-consumer/create', $request);
        }

        // Return appropriate session data/feedback and redirect
        return $this->getRedirectResponseForRequest($request, '/' . $path, 202);
    }

    /**
     * Determine whether a Reset Key was successfully set, then send an email to the ApiConsumer (if it was), and
     * redirect back with feedback.
     *
     * @param Request $request
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getPublicResetKeyResponse($request, $apiConsumer)
    {
        // If an ApiConsumer was returned, the Reset Key was successfully set
        if ($apiConsumer instanceof ApiConsumer) {
            // Queue up an email to the ApiConsumer with their reset key
            $job = (new SendApiResetKeyEmail($apiConsumer));
            $this->dispatch($job);

            // Display a success message to the ApiConsumer
            flasher()->bsSuccessDismiss('An email containing your reset key has been sent to ' . $apiConsumer->email . '. Enter the reset key in the form to refresh your API token.');
            $status = 200;
        } else {
            // The Reset Key was NOT successfully set, so show an error message
            flasher()->bsError('Whoops! We were unable to process your request. Please try again.');
            $status = $apiConsumer->getStatusCode();
        }

        // Return appropriate session data/feedback and reload the page
        return $this->getReloadResponseForRequest($request, $status);
    }

    /**
     * Determine whether a Reset Key was successfully set, then flash it to the admin settings page (if it was), or
     * return the error details. ADMIN ONLY.
     *
     * @param Request $request
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAdminResetKeyResponse($request, $apiConsumer)
    {
        // If we received an ApiConsumer, the Reset Key was successfully updated
        if ($apiConsumer instanceof ApiConsumer) {
            // Add the reset key to the session to display on the page
            flasher()->bsInfo($apiConsumer->reset_key, 'Reset Key');
            $status = 200;
        } else {
            // The Reset Key was NOT successfully updated, so get the error details
            $info = getJsonInfoArray($apiConsumer);
            // Return the error details to the administrator
            flasher()->bsError('Reset Key was not generated: ' . $info['message'], $info['status'] . ' error');
            $status = $info['status'];
        }

        // Return appropriate session data/feedback and reload the page
        return $this->getReloadResponseForRequest($request, $status);
    }

    /**
     * Determine whether an ApiConsumer update was successful and redirect back with the appropriate feedback.
     *
     * @param Request $request
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getUpdateResponse($request, $apiConsumer)
    {
        // If an ApiConsumer was returned, the update was successful
        if ($apiConsumer instanceof ApiConsumer) {
            // Return a success message
            flasher()->bsSuccessDismiss('The settings have been successfully updated.');
            $status = 200;
        } else {
            // The update was NOT successful, so return an error message
            flasher()->bsError('The update was unsuccessful. Please try again.');
            $status = $apiConsumer->getStatusCode();
        }

        // Return appropriate session data/feedback and reload the page
        return $this->getReloadResponseForRequest($request, $status);
    }

    /**
     * @param Request $request
     * @param JsonResponse|array $deleted
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function getDeleteResponse($request, $deleted)
    {
        if ($deleted instanceof JsonResponse) {
            // The deletion was unsuccessful, so reload the current page
            return $this->getReloadResponseForRequest($request, $deleted->getStatusCode());
        }

        // The deletion was successful, so redirect to the ApiConsumer index page
        $path = action('ApiConsumerController@index');
        // Remove the WebAccessToken from the ApiConsumer's session
        $this->apiTokenManager->removeWebAccessToken();

        return $this->getRedirectResponseForRequest($request, $path, 200);
    }
}