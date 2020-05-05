@extends('auth.layout')
@section('title', __('Create an account'))
@section('content')
    <form class="box-auth" method="post" action="/register" name="register">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="email" class="sr-only">{{__('Email')}}</label>
                    <input class="form-control" type="email" name="email" placeholder="{{__('Enter email address')}}" required_without="[name=phone]" />
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="form-group">
                    <label for="phone" class="sr-only">{{__('Phone')}}</label>
                    <input class="form-control" name="phone" placeholder="{{__('Enter phone number')}}" required_without="[name=email]" phonenumber />
                    <i class="fa fa-phone-square"></i>
                </div>
                <div class="form-group">
                    <label for="display_name" class="sr-only">{{__('Display name')}}</label>
                    <input class="form-control" name="display_name" placeholder="{{__('Enter display name')}}" required />
                    <i class="fa fa-user"></i>
                </div>
                <div class="form-group">
                    <label for="password" class="sr-only">{{__('Password')}}</label>
                    <input class="form-control" type="password" name="password" placeholder="{{__('Enter password')}}" minlength="{{config('site.password_length')}}" required />
                    <i class="fa fa-key"></i>
                </div>
                <div class="form-group">
                    <label for="password_confirmed" class="sr-only">{{__('Password confirmed')}}</label>
                    <input class="form-control" type="password" name="password_confirmation" placeholder="{{__('Re enter password')}}" equalTo="[name=password]" required />
                    <i class="fa fa-undo"></i>
                </div>
                <a href="{{url('login')}}" class="text-muted">{{__('Already have account?')}}</a>
                <button class="btn btn-primary pull-right">{{__('Register')}}</button>
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
    <div class="box-auth-message">
        <div class="alert alert-success" role="alert">
            <h4><i class="icon fa fa-check"></i> {{__('Thank you!')}}</h4>
            <p>{{__('You have been successfully registered.')}}</p>
        </div>
        <p class="text-center">{{__('Redirecting to home page.')}}</p>
        <div class="progress progress-striped active">
            <div class="progress-bar progress-bar-info" style="width: 100%"></div>
        </div>
    </div>
@endsection
