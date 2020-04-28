<?php namespace Http\Controllers\Backend;

use Illuminate\Http\Request;

class DashboardController extends \App\Http\Controllers\Controller
{

    public function index()
    {
        return view('Backend::dashboard');
    }
}
