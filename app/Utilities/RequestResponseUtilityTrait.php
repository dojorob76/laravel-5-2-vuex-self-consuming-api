<?php

namespace App\Utilities;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait RequestResponseUtilityTrait
{

    /**
     * The paths for the various authentication routes (Login Route, Register Route, Logout Route).
     *
     * @var array
     */
    public $authRoutes = ['authenticate', 'logout'];

    /**
     * The name of the admin subdomain (i.e., 'admin.myapp.com' where 'admin' is the name)
     *
     * @var string
     */
    public $adminSubdomain = 'admin';

    /**
     * @param Request|null $request
     * @return $this|Request
     */
    public function getRequestInstance(Request $request = null)
    {
        // If a request object was not provided, capture the request
        if ($request == null) {
            $captured = Request::capture();
            $request = $captured->instance();
        }

        return $request;
    }

    /**
     * Get and return the subdomain (if there is one, and it isn't 'www') from a Request.
     *
     * @param null|Request $r
     * @return string|null
     */
    public function getSubdomain(Request $r = null)
    {
        $request = $this->getRequestInstance($r);

        $host = $request->server('HTTP_HOST');
        $host_parts = explode('.', $host);

        $subdomain = count($host_parts) > 2 ? $host_parts[0] : null;

        if ($subdomain == 'www') {
            $subdomain = null;
        }

        return $subdomain;
    }

    /**
     * @param string $path
     * @param Request|null $r
     * @return string
     */
    public function getPathForSubdomain($path, Request $r = null)
    {
        $request = $this->getRequestInstance($r);

        // Get the subdomain that we are currently in
        $subdomain = $this->getSubdomain($request);

        // Get the url protocol to build an absolute path
        $h = env('URL_PROTOCOL');

        // Make sure we're not already dealing with an absolute path
        if (substr($path, 0, strlen($h)) == $h) {
            $removeChars = strlen($h . $request->server('HTTP_HOST'));
            // Set the path variable without the Protocol and Host
            $path = substr($path, $removeChars);
        }

        // Prepend the '/' to the path if it is not already there for use in absolute URL
        if (substr($path, 0, 1) != '/') {
            $path = '/' . $path;
        }

        // If we are trying to reach an Auth Route, build the path now
        if (in_array(ltrim($path, '/'), $this->authRoutes)) {
            return $h . env('APP_MAIN') . $path;
        }

        // Build up the correct path for a subdomain
        $subPath = '/' . $subdomain . '-' . ltrim($path, '/');

        // Only do this if we are not trying to access the home page
        if ($path != '/') {
            // Generate the appropriate absolute path based on sub(or main)domain
            $route = !$subdomain ? $h . env('APP_MAIN') . $path : $h . $subdomain . env('SESSION_DOMAIN') . $subPath;
        } else {
            $route = $h . env('APP_MAIN');
        }

        return $route;
    }

    /**
     * @param string $path
     * @param int $status
     * @param Request|null $r
     * @param array|null $msg
     * @param \Symfony\Component\HttpFoundation\Cookie|null $cookie
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function getRedirectResponseForRequest($path, $status, Request $r = null, $msg = null, $cookie = null)
    {
        // Capture the Request if it was not provided
        $request = $this->getRequestInstance($r);

        // Determine whether we are in a subdomain and prepend the prefix if necessary
        $subPath = $this->getPathForSubdomain($path, $request);

        // If we have an AJAX request, include the redirect directive and path in the response
        if ($request->ajax() || $request->wantsJson()) {
            $responseArray = ['redirector' => $subPath];
            if ($msg != null && is_array($msg)) {
                foreach ($msg as $key => $value) {
                    $responseArray[$key] = $value;
                }
            }

            return response()->json($responseArray, $status);
        }

        // Otherwise, just redirect directly
        return $cookie == null ? redirect($subPath) : redirect($subPath)->withCookie($cookie);
    }

    /**
     * @param int $status
     * @param Request|null $r
     * @param array|null $msg
     * @param \Symfony\Component\HttpFoundation\Cookie|null $cookie
     * @return JsonResponse|RedirectResponse
     */
    public function getReloadResponseForRequest($status, Request $r = null, $msg = null, $cookie = null)
    {
        $request = $this->getRequestInstance($r);

        // If we have an AJAX request, include the reload directive in the response
        if ($request->ajax() || $request->wantsJson()) {
            $responseArray = ['reloader' => true];
            if ($msg != null && is_array($msg)) {
                foreach ($msg as $key => $value) {
                    $responseArray[$key] = $value;
                }
            }

            return response()->json($responseArray, $status);
        }

        // Otherwise, just redirect back
        return $cookie == null ? redirect()->back() : redirect()->back()->withCookie($cookie);
    }

    /**
     * Return the information from a JsonResponse in an array.
     *
     * @param JsonResponse $json
     * @return array
     */
    public function getJsonInfoArray(JsonResponse $json)
    {
        $status = $json->getStatusCode();
        $data = $json->getData();
        $message = $data->message;

        return ['message' => $message, 'status' => $status];
    }

    /**
     * Set the status code for a JsonResponse based on the error provided by an Exception (or not) and a backup code.
     *
     * @param Exception $e
     * @param int $backup
     * @return int
     */
    public function setStatus(Exception $e, $backup)
    {

        $status = $e instanceof HttpException ? $e->getStatusCode() : $e->getCode();

        if (!$status || $status > 530) {
            return $backup;
        }

        return $status;
    }

    /**
     * @param Exception $e
     * @param int $backup
     * @param null|string $message
     * @return JsonResponse
     */
    public function getJsonErrorForException(Exception $e, $backup, $message = null)
    {
        $status = $this->setStatus($e, $backup);

        $msg = $message ?: $e->getMessage();

        return response()->json(['message' => $msg], $status);
    }
}