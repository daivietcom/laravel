@extends('auth.layout')
@section('title', __('Forgot password'))
@section('content')
    <form class="box-auth" method="post" action="/forgot-password" name="forgot">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info" role="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>{{__('Enter your Email and instructions will be sent to you!')}}</p>
                </div>
                <div class="form-group">
                    <label for="email" class="sr-only">{{__('Email')}}</label>
                    <input class="form-control" type="email" name="email" placeholder="{{__('Enter email address')}}" required />
                    <i class="fa fa-envelope"></i>
                </div>
                <a href="{{url('login')}}" class="text-muted"><i class="fa fa-reply"></i> {{__('Back to login')}}</a>
                <button class="btn btn-primary pull-right">{{__('Send')}}</button>
            </div>
        </div>
    </form>
    <div class="box-auth-message">
        <div class="alert alert-success" role="alert">
            <h4><i class="icon fa fa-check"></i> {{__('Congratulations!')}}</h4>
            <p>{{__('You will receive a email instructions to help you reset your password.')}}</p>
        </div>
        <p class="text-center">{{__('Redirecting to reset password page.')}}</p>
        <div class="progress progress-striped active">
            <div class="progress-bar progress-bar-info" style="width: 100%"></div>
        </div>
    </div>
@endsection