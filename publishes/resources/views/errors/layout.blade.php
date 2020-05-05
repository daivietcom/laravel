<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mr. VnS">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="/themes/errors/images/favicon.ico"/>
    <link href="/themes/errors/css/404.css" rel="stylesheet" />
    <!--[if lt IE 9]>
    {!!show_script([
    'html5shiv/3.7.3/html5shiv.min.js' => ASSET_CDNJS,
    'respond.js/1.4.2/respond.min.js' => ASSET_CDNJS
    ])!!}
    <![endif]-->
</head>
<body>
<section>
    <div class="container">
        <div class="row row1">
            <div class="col-md-12">
                <h3 class="center capital f1 wow fadeInLeft" data-wow-duration="2s">{{__('Something went Wrong!')}}</h3>
                <h1 id="error" class="center wow fadeInRight" data-wow-duration="2s">0</h1>
                <p class="center wow bounceIn" data-wow-delay="2s">@yield('message')</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="cflask-holder" class="wow fadeIn" data-wow-delay="2800ms">
                    <span class="wow tada " data-wow-delay="3000ms"><i class="fa fa-flask fa-5x flask wow flip" data-wow-delay="3300ms"></i>
                        <i id="b1" class="bubble"></i>
                        <i id="b2" class="bubble"></i>
                        <i id="b3" class="bubble"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="row"> <!--Search Form Start-->
            <div class="col-md-12">
                <div class="col-md-6 col-md-offset-3 search-form wow fadeInUp" data-wow-delay="4000ms">
                    <form action="#" method="get">
                        <input type="text" placeholder="{{__('Search')}}" class="col-md-9 col-xs-12"/>
                        <input type="submit" value="{{__('Search')}}" class="col-md-3 col-xs-12"/>
                    </form>
                </div>
            </div>
        </div> <!--Search Form End-->
        <div class="row"> <!--Links Start-->
            <div class="col-md-12">
                <div class="links-wrapper">
                    <ul class="links text-center">
                        <li class="wow fadeInRight" data-wow-delay="4500ms"><a href="{{url('')}}" data-toggle="tooltip" data-placement="top" title="{{ __('Home') }}"><i class="fa fa-home fa-2x"></i></a></li>
                    </ul>
                </div>
            </div>

        </div> <!-- Links End-->
    </div>
</section>

<!--ADDING THE REQUIRED SCRIPT FILES-->
{!! show_script([
    'jquery/3.2.1/jquery.min.js' => ASSET_CDNJS,
    'twitter-bootstrap/3.3.7/js/bootstrap.min.js' => ASSET_CDNJS,
    'countup.js/1.8.2/countUp.min.js' => ASSET_CDNJS,
    'wow/1.1.2/wow.min.js' => ASSET_CDNJS
]) !!}

<!--Initiating the CountUp Script-->
<script type="text/javascript">
    "use strict";
    var count = new CountUp("error", 0, @yield('statusCode'), 0, 3);

    window.onload = function() {
        // fire animation
        count.start();
    }
</script>

<!--Initiating the Wow Script-->
<script>
    "use strict";
    var wow = new WOW(
        {
            animateClass: 'animated',
            offset:       100
        }
    );
    wow.init();
</script>


</body>
</html>
