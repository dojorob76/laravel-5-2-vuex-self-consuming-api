<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Utilities\JwTokenManager;

class TokenRefresh
{

    protected $jwTokenManager;

    public function __construct(JwTokenManager $jwTokenManager)
    {
        $this->jwTokenManager = $jwTokenManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Attempt to retrieve a valid JWT from the request headers or query string
        $jwt = $this->jwTokenManager->getValidJwtFromRequest($request);

        if ($jwt instanceof JsonResponse) {
            // A valid JWT could not be retrieved
            $data = $jwt->getData();
            $response = new Response($data->message, $jwt->getStatusCode());

            return $response->withCookie($this->jwTokenManager->setJwtCookieExpired());
        }

        // The JWT is valid, so we need to refresh it
        $refreshed = $this->jwTokenManager->refreshJwtFromUser($jwt, $this->jwTokenManager->getUserFromJwt($jwt));
        // Make sure the Token Refresh was successful...
        if ($refreshed instanceof JsonResponse) {
            $message = 'An error occurred during the attempt to refresh this JWT.';
            $data = $refreshed->getData();
            abort($refreshed->getStatusCode(), $message . ' ' . $data->message);
        }
        // Add the refreshed JWT to the Cookies
        $cookie = $this->jwTokenManager->setJwtCookieFresh($refreshed);

        return $next($request)->header('Authorization', 'Bearer ' . $refreshed)->withCookie($cookie);
    }
}
