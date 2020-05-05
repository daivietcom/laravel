<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{image_path('favicon.ico')}}"/>
    <title>@yield('title')</title>
    {!! show_style('my.css') !!}
    <!--[if lt IE 9]>
    {!!show_script([
    'html5shiv/3.7.3/html5shiv.min.js' => ASSET_CDNJS,
    'respond.js/1.4.2/respond.min.js' => ASSET_CDNJS
    ])!!}
    <![endif]-->
    <style>
    </style>
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-menu-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/my">{{__('Profile')}}</a>
        </div>

        <div class="collapse navbar-collapse" id="navbar-menu-collapse">
            <ul class="nav navbar-nav">
                <li><a href="/">{{__('Homepage')}}</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/logout" style="color: #da251d;"><i class=" fa fa-sign-out"></i> {{__('Logout')}}</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div  class="list-group">
                <div class="list-group-item text-center">
                    <div class="avatar"><span href="#" style="background-image: url('/avatar/{{$user->id}}/128')"></span></div>
                    <h3>{{$user->display_name}}</h3>
                    <h4 class="text-info">{{__($user->group->name)}}</h4>
                    <a href="/logout" class="btn btn-danger">{{__('Logout')}}</a>
                </div>
                <a class="list-group-item {{set_active('my')}}" href="/my">
                    <i class="fa fa-info fa-fw"></i> {{__('Personal information')}}
                </a>
                <a class="list-group-item {{set_active('my/setting')}}" href="/my/setting">
                    <i class="fa fa-cogs fa-fw"></i> {{__('Account settings')}}
                </a>
            </div>
            <div class="panel panel-default panel-info">
                <div class="panel-heading"><strong><i class="fa fa-address-card fa-fw"></i> {{__('About :name', ['name' => $user->display_name])}}</strong></div>
                <div class="list-group">
                    @if(!empty($user->birthday))
                    <span class="list-group-item">
                        <i class="fa fa-birthday-cake fa-fw"></i> {{__(\Carbon\Carbon::parse($user->birthday)->format(__('m/d/Y')))}}
                    </span>
                    @endif
                    @if(!empty($user->url))
                    <a class="list-group-item" href="{{__($user->url)}}">
                        <i class="fa fa-globe fa-fw"></i> {{__($user->url)}}
                    </a>
                    @endif
                    @if(!empty($user->phone))
                    <span class="list-group-item">
                        <i class="fa fa-phone fa-fw"></i> {{__($user->phone)}}
                    </span>
                    @endif
                    @if(!empty($user->email))
                    <span class="list-group-item">
                        <i class="fa fa-envelope fa-fw"></i> {{__($user->email)}}
                    </span>
                    @endif
                    @foreach((array)$user->social as $social => $id)
                    <a class="list-group-item" href="#">
                        <i class="fa fa-{{$social}} fa-fw"></i> {{__('social_'.$social)}}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            @yield('content')
        </div>
    </div>
</div>
<script type="text/javascript">
    var BASE_URL = '{{url('')}}',
        SCRIPT_LANG = {
            'Loading, please wait...': '{{__('Loading, please wait...')}}',
            'Profile update successful.': '{{__('Profile update successful.')}}',
            'Change password successfully.': '{{__('Change password successfully.')}}',
            'Successfully change.': '{{__('Successfully change.')}}',
            'Change failed.': '{{__('Change failed.')}}'
        },
        LEN_PASS = {{config('site.password_length')}};
</script>
{!! show_script([
    'jquery/3.2.1/jquery.min.js' => ASSET_CDNJS,
    'jquery-validate/1.16.0/jquery.validate.min.js' => ASSET_CDNJS,
    'twitter-bootstrap/3.3.7/js/bootstrap.min.js' => ASSET_CDNJS,
    'mouse0270-bootstrap-notify/3.1.7/bootstrap-notify.min.js' => ASSET_CDNJS,
    'my.js' => ASSET_THEME
]) !!}
</body>
</html>
