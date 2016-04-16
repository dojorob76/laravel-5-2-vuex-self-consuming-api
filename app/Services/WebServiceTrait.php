<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;

trait WebServiceTrait
{

    /**
     * @param Request $request
     * @param string $path
     * @param int $status
     * @return JsonResponse|RedirectResponse|Redirector
     */
    public function getRedirectResponseForRequest($request, $path, $status)
    {
        // If we have an AJAX request, include the redirect route in the response
        if ($request->ajax()) {
            return response()->json(['redirector' => $path], $status);
        }

        // Otherwise, just redirect directly
        return redirect($path);
    }

    /**
     * @param Request $request
     * @param int $status
     * @return JsonResponse|RedirectResponse
     */
    public function getReloadResponseForRequest($request, $status)
    {
        // If we have an AJAX request, include the reload directive in the response
        if ($request->ajax()) {
            return response()->json(['reloader' => true], $status);
        }

        // Otherwise, just redirect back
        return redirect()->back();
    }

    /**
     * @param string $route
     * @param Request $request
     * @return string
     */
    public function getPathForSubdomain($route, $request)
    {
        // Get the subdomain that we are currently in
        $subdomain = getSubdomain($request);
        // If we are not in a subdomain, return the route, otherwise prepend the subdomain + '-' to the route
        $path = !$subdomain ? $route : $subdomain . '-' . $route;

        return $path;
    }
}