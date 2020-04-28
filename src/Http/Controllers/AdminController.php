<?php namespace Http\Controllers;

use Auth;

class AdminController extends \App\Http\Controllers\Controller
{
    public function layout()
    {
        load_lang('admin');
        set_script_path('themes/admin/js');
        set_style_path('themes/admin/css');
        set_image_path('themes/admin/images');
        return view('admin.layout');
    }
}
