<?php

namespace App\Utilities;

use Cookie;
use Session;
use App\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Encryption\Encrypter;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Repositories\User\UserRepositoryInterface;

class JwTokenManager
{

    protected $jwtAuth;
    protected $userRepo;
    protected $encrypter;

    /**
     * JwTokenManager constructor.
     *
     * @param JWTAuth $jwtAuth
     * @param UserRepositoryInterface $userRepo
     * @param Encrypter $encrypter
     */
    public function __construct(JWTAuth $jwtAuth, UserRepositoryInterface $userRepo, Encrypter $encrypter)
    {
        $this->jwtAuth = $jwtAuth;
        $this->userRepo = $userRepo;
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
        if ($user instanceof User) {
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
                return response()->json(['message' => $e->getMessage()], setStatus($e, 500));
            }

            // Return the token
            return $token;
        }

        // The User is not valid, return the error response
        return response()->json(['message' => 'This User does not exist'], 404);
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
        if ($user instanceof User) {
            // Reset the current app CSRF Token
            Session::regenerateToken();

            // Update the User's 'token_key' column to reflect the new CSRF Token
            $updatedUser = $this->userRepo->updateExisting($user->id, ['token_key' => csrf_token()]);

            // Invalidate the current JWT
            $this->jwtAuth->invalidate($jwt);

            // Generate a new JWT from the updated User
            $refreshed = $this->getJwtFromUser($updatedUser);

            // Return the refreshed JWT
            return $refreshed;
        }

        // The User is not valid, return the error response
        return response()->json(['message' => 'This User does not exist'], 404);
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
            return response()->json(['message' => 'JWT has expired'], setStatus($e, 401));
        } catch (TokenInvalidException $e) {
            // The token is invalid, return the error response
            return response()->json(['message' => 'JWT is invalid'], setStatus($e, 401));
        } catch (JWTException $e) {
            // An exception was thrown while attempting to decode the JWT, return the error response
            return response()->json(['message' => $e->getMessage()], setStatus($e, 500));
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
        $jwt = null;
        if ($request->hasCookie('jwt')) {
            $jwt = $request->cookie('jwt');
        } else {
            $cookies = $request->header('cookie');
            $cookieParts = explode(' ', $cookies);

            foreach ($cookieParts as $crumb) {
                if (substr($crumb, 0, 4) === 'jwt=') {
                    $jwt = trim(ltrim($crumb, 'jwt='));
                    break;
                }
            }
        }

        return $jwt;
    }

    /**
     * Attempt to retrieve a JWT via JWTAuth methods, and if we cannot find it, try the cookies.
     *
     * @param null|\Request $request
     * @return null|string
     */
    public function getJwtFromResources($request = null)
    {
        if ($request == null) {
            $request = getCapturedRequest();
        }
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
     * Set a JWT cookie on the session domain with 'Http only' set to false so that it is accessible to both PHP and JS
     * from all app subdomains.
     *
     * @param string $jwt
     * @param int $mins
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function setJwtCookieFresh($jwt, $mins = 180)
    {
        // If minutes are passed through, we will use them, otherwise we'll default to 3 hrs (180 minutes)
        return Cookie::make('jwt', $jwt, $mins, '/', env('SESSION_DOMAIN'), false, false);
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
     * Attempt to retrieve, validate, and return a User from a JWT for various Middlewares - or return an
     * Illuminate\Http\Response if retrieval or validation fails.
     *
     * @param Request $request
     * @return $this|User|\Illuminate\Http\Response
     */
    public function getJwtUserForMiddleware(Request $request)
    {
        // Get the token from the request if it exists
        if (!$jwt = $this->jwtAuth->setRequest($request)->getToken()) {
            $response1 = new \Illuminate\Http\Response('JWT Not Provided', 401);

            return $response1;
        }
        // Get the User from the token
        $user = $this->getUserFromJwt($jwt);

        if ($user instanceof User) {
            // Compare the JWT XsrfToken custom claim against the CSRF Token stored on the User
            if (!$this->compareTokenKeys($jwt, $user)) {
                // Remove the corrupted token and return the response
                $response2 = new \Illuminate\Http\Response('JWT Has Been Compromised', 401);

                return $response2->withCookie($this->removeJwt($jwt));
            }

            // The User is valid and the token comparison has passed, so return the User
            return $user;
        }

        // The User is not valid, so let's convert the JsonResponse error to a usable response object
        $error = getJsonInfoArray($user);
        $response3 = new \Illuminate\Http\Response($error['message'], $error['status']);

        return $response3;
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
        // Make sure we are dealing with a valid User
        if ($user instanceof User) {
            // Get the encrypted CSRF from the JWT xsrfToken custom claim
            $xsrf = $this->getXsrfFromJwt($jwt);
            // Get the User's 'token_key' column value
            $csrf = $user->token_key;

            // If either key is null, return false
            if ($xsrf == null || $csrf == null) {
                return false;
            }

            // Compare the *decrypted* xsrfToken with the CSRF token stored on the User
            $tokensMatch = $this->encrypter->decrypt($xsrf) === $csrf ? true : false;

            return $tokensMatch;
        }

        // The User is not valid, return false
        return false;
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
        $claims = $payload->getClaims();

        return array_key_exists('xsrfToken', $claims) ? $payload['xsrfToken'] : null;
    }

}