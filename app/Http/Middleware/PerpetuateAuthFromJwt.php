<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\User;
use App\Utilities\JwTokenManager;

class PerpetuateAuthFromJwt
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
        // Only do this if the user is not attempting to log out
        if ($request->path() != 'logout') {
            // Check for a JWT in every valid location
            $jwt = $this->jwTokenManager->getJwtFromResources($request);
            // Attempt to retrieve a User from the JWT
            $user = $jwt == null ? false : $this->jwTokenManager->getUserFromJwt($jwt);
            // Determine whether we received an actual User
            $jwtUser = !$user ? false : $user instanceof User;

            if (Auth::guest()) {
                // If the User is not authenticated, but they have a valid JWT, log them in
                if ($jwtUser) {
                    Auth::login($user);
                    // If the token expires in less than 10 minutes, refresh it
                    if ($this->jwTokenManager->getSecondsLeftOnJwt($jwt) < 601) {
                        // Refresh the JWT
                        $refreshed = $this->jwTokenManager->refreshJwtFromUser($jwt, $user);
                        if (!is_string($refreshed)) {
                            $message = 'An error occurred during the attempt to refresh this JWT.';
                            $data = $refreshed->getData();
                            abort($refreshed->getStatusCode(), $message . ' ' . $data->message);
                        }

                        // Add the new JWT to the Cookies with the response
                        return $next($request)->withCookie($this->jwTokenManager->setJwtCookieFresh($refreshed));
                    }
                }
            } else {
                // If the User is authenticated, but they do not have a valid JWT, log them out
                if ($jwt == null || !$jwtUser || Auth::user()->id != $user->id) {
                    Auth::logout();
                }
            }
        }

        return $next($request);
    }
}
