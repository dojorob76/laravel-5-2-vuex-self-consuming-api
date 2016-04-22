<?php

namespace App\Http\Middleware;

use Closure;
use Silber\Bouncer\Bouncer;
use App\Utilities\RequestResponseUtilityTrait;

class AdminSubdomain
{

    use RequestResponseUtilityTrait;

    protected $bouncer;

    // The initial landing page of the admin submdomain - change as needed
    protected $adminLanding = 'admin-dashboard';

    // The name of the Bouncer ability to access the admin subdomain - change as needed
    protected $accessAbility = 'access-admin-subdomain';

    /**
     * AdminSubdomain constructor.
     *
     * @param Bouncer $bouncer
     */
    public function __construct(Bouncer $bouncer)
    {
        $this->bouncer = $bouncer;
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
        $route = $request->path();
        $adminPrefix = $this->adminSubdomain . '-';

        // Determine whether or not we are trying to access the admin subdomain
        if ($this->getSubdomain($request) == $this->adminSubdomain) {
            // If the user does not have permission to access the admin subdomain, abort with message
            if ($this->bouncer->denies($this->accessAbility)) {
                abort(401, 'Only system administrators may access this page.');
            }

            $appMain = env('URL_PROTOCOL') . env('APP_MAIN');

            if (strlen($route) > 1) {
                // If this is an Auth Route, make sure the admin subdomain is stripped
                if (in_array($route, $this->authRoutes)) {
                    return $this->getRedirectResponseForRequest($route, 302, $request);
                }

                // If the route does not have the 'admin-' prefix, prepend it now
                if (substr($route, 0, strlen($adminPrefix)) != $adminPrefix) {
                    return $this->getRedirectResponseForRequest($route, 302, $request);
                }
            } else {
                // We are trying to reach the Main App landing page, so strip the admin subdomain
                return $this->getRedirectResponseForRequest($appMain, 302, $request);
            }
        }

        return $next($request);
    }
}
