<?php namespace Http\Controllers;

use Illuminate\Http\Request;
use Models\Post;
use Models\Term;

class CastController extends \App\Http\Controllers\Controller
{

    public function castPost(Request $request)
    {
        $parameters = $request->route()->parameters;
        $post = \Cache::remember('castPost'.implode('-', $parameters), cache_time(), function () use($parameters) {
          return Post::where(function ($q) use ($parameters){
                  foreach ($parameters as $key => $value) {
                      $q->where('posts.'.$key, $value);
                  }
              })
              ->leftJoin('users', 'posts.created_by', '=', 'users.id')
              ->leftJoin('terms', 'posts.parent', '=', 'terms.id')
              ->select('posts.*', 'users.display_name as auth', 'terms.name as category_name', 'terms.slug as category_slug', 'terms.id as category_id')
              ->first()
          ;
        });

        if(empty($post)) {
            return abort(404);
        } else {
            $cast = config('site.cast.post');
            if (array_key_exists($post->model, $cast)) {
                return app($cast[$post->model])->callAction('displayPost', [cast_object($post,'Models\Post',$post->model)]);
            } else {
                return abort(404);
            }
        }
    }

    public function castCategory(Request $request)
    {
        $parameters = $request->route()->parameters;
        if(isset($parameters['page'])) {
            $page = $parameters['page'];
            unset($parameters['page']);
        } else {
            $page = 1;
        }
        $category = \Cache::remember('castCategory'.implode('-', $parameters), cache_time(), function () use($parameters) {
          return Term::where(function ($q) use ($parameters){
                  foreach ($parameters as $key => $value) {
                      $q->where('terms.'.$key, $value);
                  }
              })
              ->select('terms.*')
              ->first()
          ;
        });

        if(empty($category)) {
            return abort(404);
        } else {
            $cast = config('site.cast.category');
            if (array_key_exists($category->model, $cast)) {
                return app($cast[$category->model])->callAction('displayCategory', [cast_object($category,'Models\Term',$category->model), $page]);
            } else {
                return abort(404);
            }
        }
    }
}
