<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{image_path('favicon.ico')}}"/>
    <title>@yield('title')</title>
    {!! show_style('auth.css') !!}
    <!--[if lt IE 9]>
    {!!show_script([
    'html5shiv/3.7.3/html5shiv.min.js' => ASSET_CDNJS,
    'respond.js/1.4.2/respond.min.js' => ASSET_CDNJS
    ])!!}
    <![endif]-->
</head>
<body>
<div>
    <div class="box-title">
        {!! show_image('logo_h50.png', ['class'=>'logo']) !!}<br>
        <h2>@yield('title')</h2>
    </div>
    @yield('content')
</div>
<script type="text/javascript">
    var BASE_URL = '{{url('')}}',
        SCRIPT_LANG = {
            'Loading, please wait...': '{{__('Loading, please wait...')}}',
            'This field is required.': '{{__('This field is required.')}}',
            'Please enter the email address or phone number.': '{{__('Please enter the email address or phone number.')}}',
            'Please enter a valid email address.': '{{__('Please enter a valid email address.')}}',
            'Please enter at least :minlength characters.': '{{__('Please enter at least :minlength characters.')}}',
            'Please enter the password again.': '{{__('Please enter the password again.')}}',
            'Please enter a valid format.': '{{__('Please enter a valid format.')}}',
            'Not a valid phone number.': '{{__('Not a valid phone number.')}}'
        },
        LEN_PASS = {{config('site.password_length')}};
</script>
{!! show_script([
    'jquery/3.2.1/jquery.min.js' => ASSET_CDNJS,
    'jquery-validate/1.16.0/jquery.validate.min.js' => ASSET_CDNJS,
    'twitter-bootstrap/3.3.7/js/bootstrap.min.js' => ASSET_CDNJS,
    'auth.js' => ASSET_THEME
]) !!}
</body>
</html>
