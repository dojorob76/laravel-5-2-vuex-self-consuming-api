<?php

namespace App\Http\Middleware;

use App\Utilities\RequestResponseUtilityTrait;
use Closure;
use Silber\Bouncer\Bouncer;

class AdminSubdomain
{

    use RequestResponseUtilityTrait;

    protected $bouncer;

    // The name of the admin subdomain (i.e., 'admin.myapp.com' where 'admin' is the name) - change as needed
    protected $adminSubdomain = 'admin';

    // The initial landing page of the admin submdomain - change as needed
    protected $adminLanding = 'admin-dashboard';

    // The name of the Bouncer ability to access the admin subdomain - change as needed
    protected $accessAbility = 'access-admin-subdomain';

    /**
     * AdminSubdomain Middleware constructor.
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

            if (strlen($route) > 1) {
                // If the route does not have the 'admin-' prefix, prepend it now
                if($route != 'logout' && substr($route, 0, strlen($adminPrefix)) != $adminPrefix){
                    return $this->getRedirectResponseForRequest($route, 302, $request);
                }
            } else {
                // We are trying to reach the Main App landing page, so ditch the admin subdomain now
                return $this->getRedirectResponseForRequest(env('URL_PROTOCOL') . env('APP_MAIN'), 302, $request);
            }
        }

        return $next($request);
    }
}
