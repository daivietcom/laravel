<?php namespace Http\Controllers\Backend;

use Illuminate\Http\Request;
use Cache;

class CacheController extends \App\Http\Controllers\Controller
{

    public function postClear($key) {
        Cache::forget($key);
        return response()->json(['success' => true]);
    }
}
