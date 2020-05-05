@extends('Frontend::layouts.full')
@section('content')
    @if(!empty($hooks = config('hooks.frontend.home')))
        @foreach($hooks as $hook)
            @include($hook)
        @endforeach
    @endif
    @gadgets('home.top')
    <div class="featurette">
        <div class="container">
            <h2 class="featurette-title">{{__('Designed for everyone, everywhere.')}}</h2>
            <p class="lead">Bootstrap makes front-end web development faster and easier. It's made for folks of all skill levels, devices of all shapes, and projects of all sizes.</p>
            <hr class="half-rule">
            <div class="row">
                <div class="col-sm-4">
                    <img alt="Sass and Less support" src="http://getbootstrap.com/assets/img/sass-less.png" class="img-responsive">
                    <h3>Preprocessors</h3>
                    <p>Bootstrap ships with vanilla CSS, but its source code utilizes the two most popular CSS preprocessors,
                        <a href="../css/#less">Less</a> and
                        <a href="../css/#sass">Sass</a>. Quickly get started with precompiled CSS or build on the source.
                    </p>
                </div>
                <div class="col-sm-4">
                    <img alt="Responsive across devices" src="http://getbootstrap.com/assets/img/devices.png" class="img-responsive">
                    <h3>One framework, every device.</h3>
                    <p>Bootstrap easily and efficiently scales your websites and applications with a single code base, from phones to tablets to desktops with CSS media queries.</p>
                </div>
                <div class="col-sm-4">
                    <img alt="Components" src="http://getbootstrap.com/assets/img/components.png" class="img-responsive">
                    <h3>Full of features</h3>
                    <p>With Bootstrap, you get extensive and beautiful documentation for common HTML elements, dozens of custom HTML and CSS components, and awesome jQuery plugins.</p>
                </div>
            </div>
            <hr class="half-rule">
            <p class="lead">{{__('VnSource is open source. It\'s hosted, developed, and maintained on GitHub.')}}</p>
            <a href="https://github.com/vnsource/laravel" class="btn btn-outline btn-lg">{{__('View the GitHub project')}}</a>
        </div>
    </div>
@endsection
