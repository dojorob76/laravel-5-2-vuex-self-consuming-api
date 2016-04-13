<?php

namespace App\Services\ApiConsumer;

use App\ApiConsumer;
use App\Jobs\SendApiResetKeyEmail;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ApiConsumerWebService extends ApiConsumerService
{

    use DispatchesJobs;

    /**
     * Attempt to log an ApiConsumer in to the WEB APP and return the ApiConsumer or a JsonResponse error.
     *
     * @param array $data
     * @return ApiConsumer|JsonResponse
     */
    public function logInToWebApp($data)
    {
        // Make sure we we received the correct input type
        miscastVar('array', $data, 'API Consumer web login request data');

        // Attempt to verify the API Access Token provided in the form
        if (!$this->apiTokenManager->verifyApiToken($data['api_token'])) {
            // The API Access Token is not valid, so return a JsonRespsone error
            return response()->json('The provided API Access Token is invalid', 401);
        }

        // The API Access Token is valid, so attempt to retrieve the API Consumer
        $apiConsumer = $this->getApiConsumerByEmail($data['email']);

        // Make sure we have a valid API Consumer
        if ($apiConsumer instanceof ApiConsumer) {
            // Add the PreWebAccess to the session so the ApiConsumer can access the web app on the next request
            $this->setPreWebAccessToken($data['api_token']);
        }

        // Return the ApiConsumer or the JsonResponse error if they could not be retrieved
        return $apiConsumer;
    }

    /**
     * Log an APIConsumer out of the WEB APP and redirect them to the Public API Index page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logOutOfWebApp()
    {
        // Check for the WebAccessToken in the ApiConsumer's session
        if (session()->has('api_consumer_token')) {
            // Remove the token from the session
            session()->pull('api_consumer_token');
        }
        // Generate a successful log out message for the ApiConsumer
        flasher()->vueSuccessDismiss('You are now logged out of your API Account.', 'Goodbye');

        // Redirect to the ApiConsumer index page
        return redirect(action('ApiConsumerController@index'));
    }

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
            return $this->apiTokenManager->getApiConsumerFromToken(session('api_consumer_token'));
        }

        // If the WebAccessToken is not in the session, the ApiConsumer is not logged in
        return false;
    }

    /**
     * Determine whether an ApiConsumer has successfully logged in, and redirect accordingly with appropriate
     * feedback and session variables.
     *
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getWebAccessRoute($apiConsumer)
    {
        // If we received an ApiConsumer, log in was successful
        if ($apiConsumer instanceof ApiConsumer) {
            // Add the validatable WebAccessToken to the ApiConsumer's session so they can access the WEB APP
            $this->setValidWebAccessToken();
            // Display a success message
            flasher()->vueSuccessTimed('You are now logged in to your API Account.', 'Welcome!');

            // Redirect the ApiConsumer to their account management page
            return redirect(action('ApiConsumerController@show', $apiConsumer->id));
        }
        // The log in was NOT successful, so return an error message
        flasher()->vueErrorDismiss('Log in was unsuccessful. Please try again.', 'Whoops!');

        // Redirect to the Public API landing page
        return redirect(action('ApiConsumerController@index'));
    }

    /**
     * Determine whether an ApiConsumer's starter token was successfully updated, and redirect accordingly with
     * feedback and related session variables.
     *
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getStarterTokenRoute($apiConsumer)
    {
        // If an ApiConsumer was returned, the starter token was successfully updated
        if ($apiConsumer instanceof ApiConsumer) {
            // Get a valid (human-readable) API Access Token to display ONCE for the API Consumer
            $accessToken = $this->apiTokenManager->generateValidApiToken($apiConsumer);

            // Add the access token and the ApiConsumer's ID to the session for the activation form
            session()->flash('access_token', $accessToken);
            session()->flash('access_id', $apiConsumer->id);

            // Display an instructional message to the ApiConsumer
            flasher()->vueInfo('Your new API Access Token has been created, but is not yet active. Please record and activate it now.',
                'Step One Complete');

            // If we are not in the admin subdomain, add the PreWebAccess token to the ApiConsumer's session
            if (getSubdomain() != 'admin') {
                $this->setPreWebAccessToken($accessToken);
            }

            // Determine whether we are in a subdomain and set the correct activation path accordingly
            $path = getPathForSubdomain('api-consumer/activate');

            // Redirect to the appropriate path
            return redirect('/' . $path);
        }
        // The starter token was not successfully updated, so return an error message to the ApiConsumer
        flasher()->vueError('Whoops! Something went wrong. Please try again.');
        // Determine whether we are in a subdomain and set the correct reactivation path accordingly
        $path = getPathForSubdomain('api-consumer/reactivate');

        // Redirect to the appropriate path
        return redirect('/' . $path);
    }

    /**
     * Determine whether API Access Token activation was successful and redirect accordingly with feedback and related
     * session variables.
     *
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getActivationRoute($apiConsumer)
    {
        // If an ApiConsumer was returned, activation was successful
        if ($apiConsumer instanceof ApiConsumer) {
            // Return a success message to the ApiConsumer
            flasher()->vueSuccessTimed('Your New API Access Token is now active!');
            // If we are not in the admin subdomain, add the validatable WebAccessToken to the ApiConsumer's session
            if (getSubdomain() != 'admin') {
                $this->setValidWebAccessToken();
            }
            // Determine whether we are in a subdomain and set the correct API Consumer Management page path accordingly
            $path = getPathForSubdomain('api-consumer/' . $apiConsumer->id);

            // Redirect to the appropriate API Consumer Management page
            return redirect('/' . $path);
        }
        // Activation was not successful, so return an error message to the ApiConsumer
        flasher()->vueError('Activation was unsuccessful. Please try again.');
        // Determine whether we are in a subdomain and set the correct reactivation path accordingly
        $path = getPathForSubdomain('api-consumer/reactivate');

        // Redirect to the appropriate path
        return redirect('/' . $path);
    }

    /**
     * Determine which route to send a reactivation request through based on the email provided, then redirect to
     * that page with corresponding session variables and feedback.
     *
     * @param string $email
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getReactivationRoute($email)
    {
        // Determine whether the email is associated with an ApiConsumer
        $apiConsumer = $this->getApiConsumerByEmail($email);

        if ($apiConsumer instanceof ApiConsumer) {
            // We have an ApiConsumer, let's find out if they currently have a starter token
            if ($this->apiTokenManager->getTokenStatus($apiConsumer->api_token) == 'starter') {
                // The ApiConsumer currently has a starter token, so send them through the starter token route
                return $this->getStarterTokenRoute($apiConsumer);
            }
            // The ApiConsumer with this email address currently has an active token, so display that message
            flasher()->vueErrorDismiss('The API Access Token associated with this email address is already active.');
            // Determine whether we are in a subdomain and set the correct API Consumer Management page path accordingly
            $path = getPathForSubdomain('api-consumer/' . $apiConsumer->id);

            // Redirect to the appropriate API Consumer Management page
            return redirect('/' . $path);
        }
        // The email address is not associated with an existing ApiConsumer, so display that message
        flasher()->vueInfo('Our records indicate that this email address does not currently have an API Access Token associated with it. Please generate a new token now.');
        // Flash the email address to the session to be used in the create form
        session()->flash('email_address', $email);
        // Determine whether we are in a subdomain and set the correct create path accordingly
        $path = getPathForSubdomain('api-consumer/create');

        // Redirect to the appropriate Create Page
        return redirect('/' . $path);
    }

    /**
     * Determine whether a Reset Key was successfully set, then send an email to the ApiConsumer (if it was), and
     * redirect back with feedback.
     *
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getPublicResetKeyRoute($apiConsumer)
    {
        // If an ApiConsumer was returned, the Reset Key was successfully set
        if ($apiConsumer instanceof ApiConsumer) {
            // Queue up an email to the ApiConsumer with their reset key
            $job = (new SendApiResetKeyEmail($apiConsumer));
            $this->dispatch($job);

            // Display a success message to the ApiConsumer
            flasher()->vueSuccessDismiss('An email containing your reset key has been sent to ' . $apiConsumer->email . '. Enter the reset key in the form to refresh your API token.');

            return redirect()->back();
        }
        // The Reset Key was NOT successfully set, so show an error message
        flasher()->vueError('Whoops! We were unable to process your request. Please try again.');

        return redirect()->back();
    }

    /**
     * Determine whether a Reset Key was successfully set, then flash it to the admin settings page (if it was), or
     * return the error details. ADMIN ONLY.
     *
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAdminResetKeyRoute($apiConsumer)
    {
        // If we received an ApiConsumer, the Reset Key was successfully updated
        if ($apiConsumer instanceof ApiConsumer) {
            // Add the reset key to the session to display on the page
            flasher()->vueInfo($apiConsumer->reset_key, 'Reset Key');

            return redirect()->back();
        }
        // The Reset Key was NOT successfully updated, so get the error details
        $info = getJsonInfoArray($apiConsumer);
        // Return the error details to the administrator
        flasher()->vueError('Reset Key was not generated: ' . $info['message'], $info['status'] . ' error');

        return redirect()->back();
    }

    /**
     * Determine whether an ApiConsumer update was successful and redirect back with the appropriate feedback.
     *
     * @param ApiConsumer|JsonResponse $apiConsumer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getUpdateRoute($apiConsumer)
    {
        // If an ApiConsumer was returned, the update was successful
        if ($apiConsumer instanceof ApiConsumer) {
            // Return a success message
            flasher()->vueSuccessTimed('The settings have been successfully updated.');

            return redirect()->back();
        }
        // The update was NOT successful, so return an error message
        flasher()->vueError('The update was unsuccessful. Please try again.');

        return redirect()->back();
    }

    /**
     * Store the human-readable, valid API Access Token in the session so that it may be converted to a valid web access
     * token to be used on future requests, but does not interfere with accessibility in the meantime.
     *
     * @param string $token
     */
    private function setPreWebAccessToken($token)
    {
        // First, remove previous (no longer valid) WebAccessToken from session if it exists
        if (session()->has('api_consumer_token')) {
            session()->pull('api_consumer_token');
        }
        // Set the PreWebAccessToken in the session
        session()->put('consumer_token', $token);
    }

    /**
     * Convert a PreWebAccessToken to a validatable WebAccessToken that an API Consumer can access the WEB APP with.
     */
    private function setValidWebAccessToken()
    {
        // Check for the PreWebAccessToken
        if (session()->has('consumer_token')) {
            // Remove the PreWebAccessToken
            $requestToken = session()->pull('consumer_token');
            // Set the validatable WebAccessToken
            session()->put('api_consumer_token', $requestToken);
        }
    }
}