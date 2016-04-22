<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Utilities\JwTokenManager;

class TokenAuth
{

    protected $jwTokenManager;

    public function __construct(JwTokenManager $jwTokenManager)
    {
        $this->jwTokenManager = $jwTokenManager;
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
        // Attempt to retrieve a valid JWT from the request headers or query string
        $jwt = $this->jwTokenManager->getValidJwtFromRequest($request);

        if ($jwt instanceof JsonResponse) {
            // A valid JWT could not be retrieved
            $data = $jwt->getData();
            $response = new Response($data->message, $jwt->getStatusCode());

            return $response->withCookie($this->jwTokenManager->setJwtCookieExpired());
        }

        // A valid JWT was available, so add it to the Authorization header and cookies
        $cookie = $this->jwTokenManager->setJwtCookieFresh($jwt);

        return $next($request)->header('Authorization', 'Bearer ' . $jwt)->withCookie($cookie);
    }
}
