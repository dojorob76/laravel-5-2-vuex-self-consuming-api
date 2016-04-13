<?php

namespace App\Http\Middleware;

use Closure;
use App\Utilities\ApiTokenManager;

class ApiAccessToken
{

    protected $apiTokenManager;

    /**
     * ApiAccessToken Middleware constructor.
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
        // If a valid API Access Token is not present in the request, abort with error message
        if (!$request->has('api_access_token') || !$this->apiTokenManager->verifyApiToken($request->get('api_access_token'))) {
            abort(401, 'Token Required');
        }

        return $next($request);
    }
}
