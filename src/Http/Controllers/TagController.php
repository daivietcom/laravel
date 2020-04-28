<?php namespace Http\Controllers;

use Models\Tag;
use Models\Post;

class TagController extends FrontEndController
{
    public function show($slug, $page = 1) {
        $tag = Tag::findBySlug($slug);

        if(!isset($tag->name)) {
            $tag = new Tag([
                'name' => str_replace('-', ' ', $slug),
                'slug' => $slug
            ]);
        }

        $data = [
            'tag' => $tag,
            'list' => Post::where('id', 0)
                ->paginate(config('site.per_page')),
            'breadcrumb' => breadcrumb($tag->name)
        ];

        return view('Frontend::tag', $data);
    }
}
