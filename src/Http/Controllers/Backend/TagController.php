<?php namespace Http\Controllers\Backend;

use Illuminate\Http\Request;
use Models\Tag;

class TagController extends \App\Http\Controllers\Controller
{
    public function getQuery($query = '')
    {
        $tag = Tag::select('name', 'slug');
        if(!empty($query)) {
            $tag->where('name', 'like', "%$query%");
        }
        $tag->limit(10);
        return response()->json($tag->get());
    }
}
