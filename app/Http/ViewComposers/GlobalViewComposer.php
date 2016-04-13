<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

class GlobalViewComposer
{

    public function compose(View $view)
    {
        // List of variables that should be included on every page load
        $dispatcher = app('Dingo\Api\Dispatcher');
        $appRoot = env('URL_PROTOCOL') . env('APP_MAIN');
        $adminRoot = env('URL_PROTOCOL') . 'admin' . env('SESSION_DOMAIN');
        $siteName = env('SITE_NAME');

        // Add each item to every view
        $view->with([
            'dispatcher' => $dispatcher,
            'app_root'   => $appRoot,
            'admin_root' => $adminRoot,
            'site_name'  => $siteName
        ]);
    }
}