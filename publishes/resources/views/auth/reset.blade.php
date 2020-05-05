@extends('auth.layout')
@section('title', __('Reset password'))
@section('content')
    <form class="box-auth" method="post" action="/reset-password" name="reset">
        <div class="row">
            <div class="col-sm-12">
                @if(empty($token))
                    <div class="alert alert-info" role="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <p>{{__('Enter token in the instruction email sent to you!')}}</p>
                    </div>
                @endif
                <div class="form-group">
                    <label for="email" class="sr-only">{{__('Email')}}</label>
                    <input class="form-control" type="email" value="{{$email}}" name="email" placeholder="{{__('Enter email address')}}" required />
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="form-group">
                    <label for="email" class="sr-only">{{__('Token')}}</label>
                    <input class="form-control" type="text" value="{{$token}}" name="token" placeholder="{{__('Enter token')}}" required />
                    <i class="fa fa-barcode"></i>
                </div>
                <div class="form-group">
                    <label for="password" class="sr-only">{{__('Password')}}</label>
                    <input class="form-control" type="password" name="password" placeholder="{{__('Enter password')}}" minlength="{{config('site.password_length')}}" required />
                    <i class="fa fa-key"></i>
                </div>
                <div class="form-group">
                    <label for="password_confirmed" class="sr-only">{{__('Confirm password')}}</label>
                    <input class="form-control" type="password" name="password_confirmation" placeholder="{{__('Re enter password')}}" equalTo="[name=password]" required />
                    <i class="fa fa-undo"></i>
                </div>
                <button class="btn btn-primary pull-right">{{__('Reset')}}</button>
            </div>
        </div>
    </form>
    <div class="box-auth-message">
        <div class="alert alert-success" role="alert">
            <h4><i class="icon fa fa-check"></i> {{__('Congratulations!')}}</h4>
            <p>{{__('Your password has been reset successfully.')}}</p>
        </div>
        <p class="text-center">{{__('Redirecting to home page.')}}</p>
        <div class="progress progress-striped active">
            <div class="progress-bar progress-bar-info" style="width: 100%"></div>
        </div>
    </div>
@endsection