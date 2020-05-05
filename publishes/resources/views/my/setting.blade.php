@extends('my.layout')
@section('title', __('Account settings'))
@section('content')
    <div class="page-header">
        <h1>{{__('Account settings')}}</h1>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <fieldset>
                        <legend>{{__('Api token')}}</legend>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-password" type="button"><i class="fa fa-eye-slash"></i></button>
                                </span>
                                <input type="password" class="form-control" name="api_token" value="{{$user->api_token}}" readonly>
                                <span class="input-group-btn">
                                  <button class="btn btn-success" id="resetApiToken" type="button"><i class="fa fa-refresh"></i></button>
                                </span>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="/my/password" method="post" name="password">
                        <fieldset>
                            <legend>{{__('Change password')}}</legend>
                            <div class="form-group">
                                <label for="password_old" class="col-lg-6 control-label">{{__('Old password')}}</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" name="password_old" placeholder="{{__('Enter old password')}}" minlength="{{config('site.password_length')}}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-lg-6 control-label">{{__('New password')}}</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" name="password" placeholder="{{__('Enter new password')}}" minlength="{{config('site.password_length')}}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="col-lg-6 control-label">{{__('Confirm password')}}</label>
                                <div class="col-lg-6">
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="{{__('Re enter password')}}" equalTo="[name=password]" required>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">{{__('Update password')}}</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection