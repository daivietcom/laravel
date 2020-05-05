@extends('auth.layout')
@section('title', __('Log in'))
@section('content')
    <form class="box-auth" method="post" action="/login" name="login">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="email" class="sr-only">{{__('Email/Phone number')}}</label>
                    <input class="form-control" type="text" name="email" placeholder="{{__('Email or Phone number')}}" regex="^((([^<>()[\]\\.,;:\s@']+(\.[^<>()[\]\\.,;:\s@']+)*)|('.+'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))|[0-9]{10,15})$" required />
                    <i class="fa fa-user"></i>
                </div>
                <div class="form-group">
                    <label for="password" class="sr-only">{{__('Password')}}</label>
                    <input class="form-control" type="password" name="password" placeholder="{{__('Password')}}" minlength="{{config('site.password_length')}}" required />
                    <i class="fa fa-key"></i>
                </div>
                <button class="btn btn-primary pull-right">{{__('Log in')}}</button>
                <div class="checkbox"><label><input type="checkbox" name="remember" value="1" />{{__('Keep me logged in')}}</label></div>
                <div class="box-auth-link">
                    <a href="{{url('forgot-password')}}" class="text-muted"><i class="fa fa-lock"></i> {{__('Forgot your password?')}}</a>
                    <a href="{{url('register')}}" class="text-muted pull-right">{{__('Create an account')}}</a>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="box-auth-social">
                    <h3>{{__('OR')}} <br>
                        <span>{{__('Log in Using')}}</span>
                    </h3>
                    @foreach(['facebook','google','twitter','linkedin','github','bitbucket'] as $social)
                        @if(config('services.'.$social.'.enable'))
                            <a href="#{{$social}}" class="btn btn-social-icon btn-{{$social}}">
                                <i class="fa fa-{{$social}}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </form>
@endsection
