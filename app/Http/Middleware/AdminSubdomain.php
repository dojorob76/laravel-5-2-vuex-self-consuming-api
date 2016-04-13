<?php

namespace App\Http\Middleware;

use Closure;
use Silber\Bouncer\Bouncer;

class AdminSubdomain
{

    protected $bouncer;

    /**
     * AdminSubdomain Middleware constructor.
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
        // Determine whether or not we are trying to access the admin subdomain
        if (getSubdomain($request) == 'admin') {
            // If the user does not have the access-admin-subdomain ability, abort with message
            if ($this->bouncer->denies('access-admin-subdomain')) {
                abort(401, 'Only system administrators may access this page.');
            }
        }

        return $next($request);
    }
}
