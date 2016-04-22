<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Utilities\JwTokenManager;
use App\Http\Controllers\BaseController;

class AdminDashboardController extends BaseController
{

    /**
     * Construct a new Admin Controller instance.
     *
     * @param JwTokenManager $jwTokenManager
     */
    public function __construct(JwTokenManager $jwTokenManager)
    {
        parent::__construct($jwTokenManager);
    }

    public function index()
    {
        $pageTitle = env('SITE_NAME') . ' Administration Dashboard';

        return view('admin.admin-dashboard')->with(['page_title' => $pageTitle]);
    }
}
