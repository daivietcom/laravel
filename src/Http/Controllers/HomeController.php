<?php namespace Http\Controllers;

class HomeController extends FrontEndController
{
    public function index()
    {
        return view('Frontend::home');
    }
}
