<?php namespace Http\Controllers;

use VnSource\Traits\ViewTrait;

class FrontEndController extends \App\Http\Controllers\Controller
{
    use ViewTrait;

    public function __construct() {
        if (!empty($theme = config('site.theme'))) {
            set_asset_path($theme);
        } else {
            set_asset_path('themes/frontend');
        }
//        set_script_path(config('site.theme').'/js');
//        set_style_path(config('site.theme').'/css');
//        set_image_path(config('site.theme').'/images');
    }
}
