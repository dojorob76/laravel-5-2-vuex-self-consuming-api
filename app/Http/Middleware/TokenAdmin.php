<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Utilities\JwTokenManager;

class TokenAdmin
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

            // Expire any possible invalid JWT
            return $response->withCookie($this->jwTokenManager->setJwtCookieExpired());
        }

        // Make sure the User has the admin role
        $user = $this->jwTokenManager->getUserFromJwt($jwt);
        if(! $user instanceof User || $user->isNot('admin')){
            abort(401, 'Only system administrators may access this page.');
        }

        // The User is an admin with a valid JWT, so set the Authorization header and cookie
        $cookie = $this->jwTokenManager->setJwtCookieFresh($jwt);

        return $next($request)->header('Authorization', 'Bearer ' . $jwt)->withCookie($cookie);
    }
}
