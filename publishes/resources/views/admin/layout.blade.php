@if(!empty($hooks = config('hooks.admin.layout')))
    @foreach($hooks as $hook)
        @include($hook)
    @endforeach
@endif
<!DOCTYPE HTML>
<html ng-app="VnSapp">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mr. VnS">
    <link rel="shortcut icon" href="{{ image_path('favicon.ico') }}"/>
    <title ng-bind-html="$root.siteTitle">Quản trị VnSource {{config('site.version')}}</title>
    {!! show_style('styles.css') !!}
    <!--[if lt IE 9]>
    {!! show_script([
    'html5shiv/3.7.3/html5shiv.min.js' => ASSET_CDNJS,
    'respond.js/1.4.2/respond.min.js' => ASSET_CDNJS
    ]) !!}
    <![endif]-->
    <script type="text/javascript">
        var CODE_LANG = '{{config('app.locale')}}';
        var SCRIPT_LANG = {!! script_object(load_lang('adminJS', true)) !!};
        var LAST_LOGIN = {!! script_object((array)$user->last_login) !!};
        var API_TOKEN = '{{ $user->api_token }}';
        var API_URL = '{{url('api/v1')}}';
        var BASE_URL = '{{url('')}}';
        var IMAGE_PATH = '{{ image_path('') }}';
        var CSS_PATH = '{{ style_path('') }}';
        var JS_PATH = '{{ script_path('') }}';
    </script>
    {!! show_script([
        //'core.js' => ASSET_THEME,,
        'tinymce/4.5.5/tinymce.min.js' => ASSET_CDNJS,
        'angular.js/1.6.1/angular.min.js' => ASSET_CDNJS,
        'angular.js/1.6.1/i18n/angular-locale_'.config('app.locale').'.js' => ASSET_CDNJS,
        'angular-ui-router/0.4.2/angular-ui-router.min.js' => ASSET_CDNJS,
        'angular.js/1.6.1/angular-resource.min.js' => ASSET_CDNJS,
        'angular.js/1.6.1/angular-animate.min.js' => ASSET_CDNJS,
        'angular.js/1.6.1/angular-messages.min.js' => ASSET_CDNJS,
        'angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js' => ASSET_CDNJS,
        'bootstrap-ui-datetime-picker/2.5.4/datetime-picker.min.js' => ASSET_CDNJS,
        'angular-loading-bar/0.9.0/loading-bar.min.js' => ASSET_CDNJS,
        'angular-breadcrumb/0.5.0/angular-breadcrumb.min.js' => ASSET_CDNJS,
        'ng-tags-input/3.1.2/ng-tags-input.min.js' => ASSET_CDNJS,
        'angular-clipboard/1.5.0/angular-clipboard.min.js' => ASSET_CDNJS,
        'angular-ui-tree/2.22.5/angular-ui-tree.min.js' => ASSET_CDNJS,
        'angular-ui-notification/0.3.6/angular-ui-notification.min.js' => ASSET_CDNJS,
        'angular-ui-tinymce/0.0.18/tinymce.min.js' => ASSET_CDNJS
    ]) !!}
    @include('admin.script')
    <script type="text/ng-template" id="dashboard.html">
        <toast></toast>
        <div class="sidenav" ng-class="{'open': tgState}">
            <div class="logopanel">
                <a href="{{url('')}}">
                    {!! show_image('logo_h30.png') !!}
                    <span class="badge">{{config('site.version')}}</span>
                </a>
            </div>
            <div class="datewidget">{{ __('Today is') }}
                <clock format="EEEE"></clock>, <clock format="dd"></clock> <clock format="MMMM"></clock>, <clock format="y"></clock>
                <clock format="HH"></clock><span class="clock-point">:</span><clock format="mm"></clock>
            </div>
            <div class="plainwidget last-login">
                <strong >{{ __('Last login') }}</strong>
                <p><span class="fa fa-map-marker"></span> @{{last.ip}}</p>
                <p><span class="fa fa-@{{last.browser.name|lowercase}}"></span> @{{last.browser.name}} @{{last.browser.version}}
                </p>
                <p>
                    <span class="@{{getPlatform(last.os.name)}}"></span> @{{last.os.name}}@{{last.os.version == '' ? '' : ' ' + last.os.version}}
                    (@{{ last.device }})</p>
                <p><span class="fa fa-calendar"></span> @{{last.time|parseDate|date:'medium'}}</p>
            </div>
            <ul class="navbar-cpanel">
                <li class="nav-header" >{{ __('Main Navigation') }}</li>
                <li ui-sref-active-eq="active">
                    <a ui-sref="root"><i class="fa fa-dashboard fa-fw"></i> {{__('Dashboard')}}</a>
                </li>
                <li ui-sref-active-eq="active">
                    <a ui-sref="root.media"><i class="fa fa-picture-o fa-fw"></i> {{__('Media')}}</a>
                </li>
                <li ui-sref-active-eq="active">
                    <a ui-sref="root.module"><i class="fa fa-plug fa-fw"></i> {{__('Modules')}}</a>
                </li>
                <li ui-sref-active-eq="active">
                    <a ui-sref="root.theme"><i class="fa fa-paint-brush fa-fw"></i> {{__('Theme')}}</a>
                </li>
                <li ui-sref-active-eq="active open" class="dropdown">
                    <a href="javascript:void(0)">
                        <span class="fa fa-users fa-fw"></span> {{__('User')}} <span class="caret"></span>
                    </a>
                    <ul>
                        <li ng-class="{ active: ($state.current.name =='root.user') }">
                            <a ui-sref="root.user">{{__('All users')}}</a>
                        </li>
                        <li ng-class="{ active: ($state.current.name =='root.group') }">
                            <a ui-sref="root.group">{{__('Group')}}</a>
                        </li>
                    </ul>
                </li>
                <li ui-sref-active-eq="active">
                    <a ui-sref="root.config"><i class="fa fa-cogs fa-fw"></i> {{__('System configuration')}}</a>
                </li>
                <li class="nav-header" >{{ __('Module Navigation') }}</li>
                @php ($navbarCpanel = get_navbar_cpanel())
                @foreach($navbarCpanel as $navbar)
                    @if(isset($navbar['sub']))
                        <li ui-sref-active-eq="active open" class="dropdown">
                            <a href="javascript:void(0)">
                                <span class="icon {{ $navbar['icon'] }}"></span> {{ __($navbar['label']) }} <span class="caret"></span>
                            </a>
                            <ul>
                                @foreach($navbar['sub'] as $sub)
                                    <li ng-class="{ active: ($state.current.name =='{{ $sub['name'] }}') }">
                                        <a ui-sref="{{ $sub['name'] }}">{{ __($sub['label']) }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li ui-sref-active-eq="active">
                            <a ui-sref="{{ $navbar['name'] }}"><i class="icon {{ $navbar['icon'] }}"></i> {{ __($navbar['label']) }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="content" ng-style="{'min-height': $root.windowHeight+'px'}">
            <div class="content-nav">
                <nav class="navbar navbar-inverse navbar-static-top">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" ng-class="{'active': tgState}" ng-click="tgStateEvent()">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <span class="navbar-brand">{{config('site.name')}}</span>
                        </div>
                        <div class="navbar-btn">
                            <div uib-dropdown>
                                <i class="fa fa-comments" uib-dropdown-toggle></i>
                                <span class="sparrow-up"></span>
                                <ul uib-dropdown-menu class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li class="dropdown-header">{{ __('Notifications') }}</li>
                                    <li class="divider"></li>
                                </ul>
                            </div>
                            <div uib-dropdown>
                                <i class="fa fa-globe" uib-dropdown-toggle></i>
                                <span class="sparrow-up"></span>
                                <ul uib-dropdown-menu class="dropdown-menu dropdown-menu-right" role="menu">
                                    <li class="dropdown-header"></li>
                                    <li class="divider"></li>
                                </ul>
                            </div>
                            <div uib-dropdown>
                                <img ng-src="/avatar/{{$user->id}}/34" uib-dropdown-toggle/>
                                <span class="sparrow-up"></span>
                                <ul class="dropdown-menu dropdown-menu-right" uib-dropdown-menu role="menu">
                                    <li class="coin">
                                        <span>{{ __('Hi!') }}</span> {{$user->display_name}}
                                    </li>
                                    <li role="menuitem"><a href="{{ url('my') }}" target="_blank"><i class="fa fa-user"></i> {{ __('My profile') }}
                                        </a></li>
                                    <li role="menuitem"><a href="{{ url('my/setting') }}" target="_blank"><i
                                                    class="fa fa-lock"></i> {{ __('Change password') }}</a></li>
                                    <li role="menuitem"><a style="color: #da251d" href="{{ url('logout') }}"><i
                                                    class="fa fa-sign-out"></i> {{ __('Logout') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
                <div ncy-breadcrumb></div>
                <div class="content-title">
                    <i ng-if="$root.siteIcon != undefined" class="@{{$root.siteIcon}}"></i>
                    <img ng-if="$root.siteImage != undefined" ng-src="@{{$root.siteImage}}"/>
                    @{{$root.siteTitle}}
                    <small ng-if="$root.smallTitle != undefined">
                        <i class="fa fa-angle-double-right"></i>
                        @{{$root.smallTitle}}
                    </small>
                </div>
            </div>
            <div ui-view>
                <div class="container-fluid" ng-controller="DashboardCtrl">
                    <div class="alert alert-block alert-info">
                        <button type=button class=close data-dismiss=alert>
                            <i class="fa fa-times"></i>
                        </button>
                        <i class="fa fa-check"></i>
                        {{ __('Welcome to') }}
                        <strong>
                            VnSource <small>(v{{VNSOURCE_VERSION}})</small>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="footer">
            <div class="footer-left">Quản trị VnSource v{{VNSOURCE_VERSION}}</div>
            <div class="footer-right">Copyright © 2017 - VnSource. <a
                        href="https://www.facebook.com/VnSource/" >{{ __('Follow me on Facebook') }}</a></div>
        </div>
    </script>
    @include('admin.template')
    @stack('head')
</head>
<body>
    <div ui-view></div>
</body>
</html>
