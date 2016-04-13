<?php

namespace App\Http\Middleware;

use Closure;
use App\Utilities\ApiTokenManager;

class ApiConsumerIsOwner
{

    protected $apiTokenManager;

    /**
     * ApiConsumerIsOwner Middleware constructor.
     *
     * @param ApiTokenManager $apiTokenManager
     */
    public function __construct(ApiTokenManager $apiTokenManager)
    {
        $this->apiTokenManager = $apiTokenManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Instantiate the error response message to return if the following validation fails
        $response = 'You are not authorized to access this page';

        // Get the access token from the session or query depending on which subdomain we are on
        $token = $this->apiTokenManager->getTokenFromRequest($request);

        // If no token is present or a valid user can not be retrieved from the token, return the error response
        if (!$token || !$user = $this->apiTokenManager->getApiConsumerFromToken($token)) {
            abort(403, $response);
        }
        // If the ApiConsumer retrieved from the token is not the system admin, or the page owner (based on the 2nd
        // route segment, i.e., 'api-consumer/3' where 3 is the 2nd segment), return the error response
        if (!$this->apiTokenManager->verifyAdminToken($token) && $user->id != $request->segment(2)) {
            abort(403, $response);
        }

        return $next($request);
    }
}