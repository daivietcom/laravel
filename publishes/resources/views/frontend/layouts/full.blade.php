@if(!empty($hooks = config('hooks.frontend.layouts.full')))
    @foreach($hooks as $hook)
        @include($hook)
    @endforeach
@endif
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="VnSource">
    <meta property="article:author" content="https://www.facebook.com/VnSource"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{image_path('favicon.ico')}}"/>
    <title>@yield('title', config('site.title'))</title>
    <link rel="canonical" href="{{url(Request::getPathInfo())}}"/>
    <meta name="description" content="@yield('description', config('site.description'))"/>
    <meta name="image" content="@yield('image', config('site.image'))"/>
    <meta name="og:description" content="@yield('description', config('site.description'))"/>
    <meta name="og:image" content="@yield('image', config('site.image'))"/>
    <meta name="og:title" content="@yield('title', config('site.title'))"/>
    <meta name="og:url" content="{{url(Request::getPathInfo())}}"/>
    {!! show_style('styles.css?'.str_random(10)) !!}
    <!--[if lt IE 9]>
    {!!show_script([
    'html5shiv/3.7.3/html5shiv.min.js' => ASSET_CDNJS,
    'respond.js/1.4.2/respond.min.js' => ASSET_CDNJS
    ])!!}
    <![endif]-->
    @stack('head')
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-menu-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/" title="{{ config('site.name') }}">
                {!! show_image('logo_h30.png', ['class'=>'logo', 'alt'=> 'VnSource']) !!}
            </a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu-collapse">
            <ul class="nav navbar-nav">
                @gadgets('menu')
            </ul>
        </div>
    </div>
</nav>
@if(isset($breadcrumb))
    <div class="breadcrumb-root">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    {!! $breadcrumb !!}
                </div>
            </div>
        </div>
    </div>
@endif
@yield('content')
<footer>
    <div class="container">
        <ul class="footer-links">
            <li><a href="https://github.com/vnsource/laravel" rel="nofollow">GitHub</a></li>
            <li><a href="https://www.facebook.com/VnSource" rel="nofollow">Facebook</a></li>
            <li><a href="https://twitter.com/MitToVnS" rel="nofollow">Twitter</a></li>
            <li class="pull-right"><a href="#top">{{__('Back to top')}}</a></li>
        </ul>
        <p>{!!__('Made by <a href=":url">:name</a>. Contact him at <a href="mailto::email">:email</a>.', ['name' => 'Mr. VnS', 'url'=>'https://facebook.com/mit.to.vns', 'email'=>'lienhe.vns@gmail.com'])!!}</p>
        <p>{!!__('Code released under the <a href=":url" rel="license">:name</a>.', ['url'=>'https://github.com/vnsource/laravel/blob/master/LICENSE', 'name'=>'MIT License'])!!}</p>
    </div>
</footer>
<script type="text/javascript">
    var BASE_URL = '{{ url('') }}';
    var SCRIPT_LANG = {};
</script>
{!! show_script([
    'jquery/3.2.1/jquery.min.js' => ASSET_CDNJS,
    'twitter-bootstrap/3.3.7/js/bootstrap.min.js' => ASSET_CDNJS,
    'core.js' => ASSET_THEME
]) !!}
@stack('script')
</body>
</html>
