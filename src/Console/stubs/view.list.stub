@extends('Frontend::layouts.full')
@section('title', $category->name)
@section('description', str_limit($category->description,150))
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="page-header">{{ $category->name }}</h1>
                <div class="post-list">
                    @foreach($list as $post)
                        <div class="media">
                            <div class="media-left">
                                <a href="{{ cast_uri($post) }}" title="{{ $post->title }}" rel="nofollow">
                                    <img class="media-object" src="{{ get_thumb($post->image) }}" alt="{{ $post->title }}">
                                </a>
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading"><a href="{{ cast_uri($post) }}" title="{{ $post->title }}">{{ $post->name }}</a> <small><i class="fa fa-clock-o"></i> {{$post->created_at->diffForHumans()}}</small></h4>
                                <p>{{ $post->excerpt }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center">{!! $list->links() !!}</div>
            </div>
            <div class="col-md-4">
                @gadgets('right')
            </div>
        </div>
    </div>
@endsection