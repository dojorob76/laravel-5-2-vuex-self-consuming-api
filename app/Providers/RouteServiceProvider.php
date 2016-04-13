<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    protected $adminNamespace = 'App\Http\Controllers\Admin';

    protected $apiNamespace = 'App\Api\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $router->pattern('id', '[0-9]+');

        parent::boot($router);

        $router->model('api-consumer', 'App\ApiConsumer');
        $router->model('admin-api-consumer', 'App\ApiConsumer');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        /*
         * API Router
         */
        $router->group(['namespace' => $this->apiNamespace], function ($router) {
            require app_path('Api/routes.api.php');
        });

        /*
         * Web App and Subdomain routes (see method below)
         */
        $this->mapWebRoutes($router);
    }

    /**
     * Define the "web" routes for the application. These routes all receive session state, CSRF protection, etc.
     *
     * @param Router $router
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group(['middleware' => 'web'], function($router){
            /*
             * Main WEB APP Router
             */
            $router->group([
                'namespace'  => $this->namespace
            ], function ($router) {
                require app_path('Http/routes.php');
            });

            /*
             * ADMIN Subdomain Router
             */
            $router->group([
                'domain' => 'admin' . env('SESSION_DOMAIN'),
                'namespace' => $this->adminNamespace
            ], function($router){
                require app_path('Http/routes.admin.php');
            });

            /*
             * For additional subdomain routes, add the protected namespace above, then include the router groups here.
             * NOTE: If the subdomain should not receive the 'web' middleware, put it in the 'map' method above instead.
             *
             * Be sure to create routes.subdomain.php and the Subdomain directory under App\Http.
             * (Replace '(S)subdomain' with the actual subdomain name (i.e., 'mobile').
             *
             * EXAMPLE:
             * $router->group([
             *     'domain' => 'subdomain' . env('SESSION_DOMAIN'),
             *     'namespace' => $this->subdomainNamespace
             * ], function($router){
             *     require app_path('Subdomain/routes.subdomain.php')
             * });
             */
        });
    }
}