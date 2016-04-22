<?php

namespace App\Utilities;

use Cookie;
use Session;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Users\UserService;
use Illuminate\Encryption\Encrypter;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwTokenManager
{

    protected $jwtAuth;
    protected $userService;
    protected $encrypter;

    /**
     * JwTokenManager constructor.
     *
     * @param JWTAuth $jwtAuth
     * @param UserService $userService
     * @param Encrypter $encrypter
     */
    public function __construct(JWTAuth $jwtAuth, UserService $userService, Encrypter $encrypter)
    {
        $this->jwtAuth = $jwtAuth;
        $this->userService = $userService;
        $this->encrypter = $encrypter;
    }

    /**
     * Attempt to generate a JWT with an xsrfToken custom claim from a User and return it or return JsonResponse error.
     *
     * @param User $user
     * @return string|\Illuminate\Http\JsonResponse
     */
    public function getJwtFromUser(User $user)
    {
        // Make sure we are dealing with a valid User
        if (!$user instanceof User) {
            // The User is not valid, return the error response
            return response()->json(['message' => 'A valid User was not provided.'], 404);
        }

        // Get the User's encrypted token key to set as a custom claim (xsrfToken) on the JWT
        $xsrf = $this->getXsrfFromUser($user);

        // Attempt to generate a JWT from the User with the xsrfToken as a custom claim
        try {
            if (!$token = $this->jwtAuth->fromUser($user, $xsrf)) {
                // We were unable to generate the token from the User, return the error response
                return response()->json(['message' => 'Could not generate JWT from User'], 422);
            }
        } catch (JWTException $e) {
            // Something went wrong during the attempt to encode the JWT, return the error response
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }

        // Return the token
        return $token;
    }

    /**
     * Refresh a User's JWT after resetting the app CSRF and updating the User - or return a JsonResponse error.
     *
     * @param string $jwt
     * @param User $user
     * @return string|\Illuminate\Http\JsonResponse
     */
    public function refreshJwtFromUser($jwt, User $user)
    {
        // Make sure we are dealing with a valid User
        if (!$user instanceof User) {
            return response()->json(['message' => 'A valid User was not provided.'], 404);
        }
        // Reset the current app CSRF Token
        Session::regenerateToken();

        // Update the User's 'token_key' column to reflect the new CSRF Token
        $updatedUser = $this->userService->updateUserTokenKey($user->id, csrf_token());

        if ($updatedUser instanceof JsonResponse) {
            return $updatedUser;
        }

        // Invalidate the current JWT
        $this->jwtAuth->invalidate($jwt);

        // Generate a new JWT from the updated User
        $refreshed = $this->getJwtFromUser($updatedUser);

        // Return the refreshed JWT
        return $refreshed;
    }

    /**
     * Attempt to retrieve and return a User from a JWT, or return a JsonResponse error.
     *
     * @param string $jwt
     * @return User|\Illuminate\Http\JsonResponse
     */
    public function getUserFromJwt($jwt)
    {
        // Attempt to retrieve the user from the JWT
        try {
            if (!$user = $this->jwtAuth->authenticate($jwt)) {
                // The user could not be found, return the error response
                return response()->json(['message' => 'User not found'], 404);
            }
        } catch (TokenExpiredException $e) {
            // The token has expired, return the error response
            return response()->json(['message' => 'JWT has expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            // The token is invalid, return the error response
            return response()->json(['message' => 'JWT is invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            // An exception was thrown while attempting to decode the JWT, return the error response
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }

        // Return the User
        return $user;
    }

    /**
     * Check the request cookies/header cookie for a JWT and return it if it exists, else return null.
     *
     * @param Request $request
     * @return null|string
     */
    public function getJwtFromCookies(Request $request)
    {
        $jwt = getHeaderCookie($request, 'jwt=');

        if ($jwt == null) {
            if ($request->hasCookie('jwt')) {
                $jwt = $request->cookie('jwt');
            }
        }

        return $jwt;
    }

    /**
     * Attempt to retrieve a JWT via JWTAuth methods, and if we cannot find it, try the cookies.
     *
     * @param Request $request
     * @return null|string
     */
    public function getJwtFromResources(Request $request)
    {
        $provided = $this->jwtAuth->getToken();

        $jwt = $provided ?: $this->getJwtFromCookies($request);

        return $jwt;
    }

    /**
     * Invalidate a JWT, then set and return the expired cookie.
     *
     * @param string $jwt
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function removeJwt($jwt)
    {
        // Invalidate the JWT
        $this->jwtAuth->invalidate($jwt);

        // Return the expired JWT cookie
        return $this->setJwtCookieExpired();
    }

    /**
     * @param Request $request
     * @param string $jwt
     * @return bool
     */
    public function validateJwt(Request $request, $jwt)
    {
        // Get the User connected to the JWT
        $user = $this->getUserFromJwt($jwt);

        if (!$user instanceof User) {
            return false;
        }

        // Check that the Request User and the JwtUser match and compare the JWT Xsrf against the User's token key
        return $user->id == $request->user()->id && $this->compareTokenKeys($jwt, $user);
    }

    /**
     * Attempt to retrieve, validate, and return a JWT from a Request header or query string, or return a JsonResponse
     * error if retrieval or validation fails.
     *
     * @param Request $request
     * @return JsonResponse|string|array
     */
    public function getValidJwtFromRequest(Request $request)
    {
        // Get the JWT from the Header or Query String if it exists
        if (!$jwt = $this->jwtAuth->setRequest($request)->getToken()) {
            return response()->json(['message' => 'JWT not provided'], 401);
        }

        // Attempt to validate the JWT
        if (!$this->validateJwt($request, $jwt)) {
            return response()->json(['message' => 'JWT is invalid.'], 401);
        }

        return $jwt;
    }

    /**
     * Calculate and return the number of seconds remaining before a JWT expires.
     *
     * @param string $jwt
     * @return mixed
     */
    public function getSecondsLeftOnJwt($jwt)
    {
        // Get the current Unix Timestamp
        $now = time();
        // Get the JWT Payload
        $payload = $this->jwtAuth->getPayload($jwt);
        // Get the Token Expiration Unix Timestamp
        $expiration = $payload->get('exp');

        // Return the number of seconds remaining before the JWT expires
        return $expiration - $now;
    }

    /**
     * Set a JWT cookie on the session domain with 'Http only' set to false so that it is accessible to both PHP and JS
     * from all app subdomains.
     *
     * @param string $jwt
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function setJwtCookieFresh($jwt)
    {
        // If minutes are passed through, we will use them, otherwise we'll default to 3 hrs (180 minutes)
        return Cookie::make('jwt', $jwt, config('jwt.ttl', 180), '/', env('SESSION_DOMAIN'), false, false);
    }

    /**
     * Expire the JWT cookie on the session domain with 'Http only' set to false so that it is accessible to both PHP
     * and JS from all app subdomains.
     *
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function setJwtCookieExpired()
    {
        return Cookie::make('jwt', null, -2628000, '/', env('SESSION_DOMAIN'), false, false);
    }

    /**
     * Compare the xsrfToken in the JWT custom claim with the CSRF token stored on the User and return true or false
     * depending on whether or not they are both available and match.
     *
     * @param string $jwt
     * @param User $user
     * @return bool
     */
    private function compareTokenKeys($jwt, User $user)
    {
        // Get the encrypted CSRF from the JWT xsrfToken custom claim
        $xsrf = $this->getXsrfFromJwt($jwt);
        // Get the User's 'token_key' column value
        $csrf = $user->token_key;

        //Compare the *decrypted* xsrfToken with the CSRF token stored on the User
        $tokensMatch = $xsrf == null || $csrf == null ? false : $this->encrypter->decrypt($xsrf) === $csrf;

        return $tokensMatch;
    }

    /**
     * Generate and return an encrypted XsrfToken JWT Custom Claim from a User's 'token_key' column (this is the
     * current app CSRF key) or return a JsonResponse error.
     *
     * @param User $user
     * @return array
     */
    private function getXsrfFromUser(User $user)
    {
        // Encrypt the value of the User's 'token_key' column and set it as an xsrfToken custom claim
        return ['xsrfToken' => $this->encrypter->encrypt($user->token_key)];
    }

    /**
     * Retrieve and return the xsrfToken custom claim from the JWT payload if it exists, else return null.
     *
     * @param string $jwt
     * @return string|null
     */
    private function getXsrfFromJwt($jwt)
    {
        $payload = $this->jwtAuth->getPayload($jwt);
        $xsrf = $payload->get('xsrfToken');

        return $xsrf ?: null;
    }

}