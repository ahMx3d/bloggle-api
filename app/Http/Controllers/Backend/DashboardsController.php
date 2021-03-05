<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardsController extends Controller
{
    public function __construct()
    {
        if(!Auth::check()) return redirect_to('admin.show_login_form');
    }

    /**
     * Show the main dashboard view.
     *
     * @return View
     */
    public function index()
    {
        return (Auth::check())? view('backend.index'): redirect_to('admin.show_login_form');
    }
}
