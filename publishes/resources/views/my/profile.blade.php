@extends('my.layout')
@section('title', __('Personal information'))
@section('content')
    <div class="page-header">
        <h1>{{__('Personal information')}}</h1>
    </div>
    <form class="form-horizontal" action="/my" method="post" name="profile">
        <div class="form-group">
            <label for="first_name" class="col-md-3 control-label">{{__('First name')}}</label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="first_name" value="{{$user->first_name}}" placeholder="{{__('First name')}}">
            </div>
        </div>
        <div class="form-group">
            <label for="last_name" class="col-md-3 control-label">{{__('Last name')}}</label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="last_name" value="{{$user->last_name}}" placeholder="{{__('Last name')}}">
            </div>
        </div>
        <div class="form-group">
            <label for="display_name" class="col-md-3 control-label">{{__('Display name')}}</label>
            <div class="col-md-9">
                <input type="text" class="form-control" name="display_name" value="{{$user->display_name}}" placeholder="{{__('Display name')}}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="birthday" class="col-md-3 control-label">{{__('Birthday')}}</label>
            <div class="col-md-9">
                <input type="date" class="form-control" name="birthday" value="{{$user->birthday}}" placeholder="{{__('Birthday')}}">
            </div>
        </div>
        <div class="form-group">
            <label for="url" class="col-md-3 control-label">{{__('Website')}}</label>
            <div class="col-md-9">
                <input type="url" class="form-control" name="url" value="{{$user->url}}" placeholder="{{__('Website')}}">
            </div>
        </div>
        <div class="form-group">
            <label for="about" class="col-md-3 control-label">{{__('About me')}}</label>
            <div class="col-md-9">
                <textarea class="form-control" name="about" placeholder="{{__('About me')}}">{!! $user->about !!}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-9 col-md-offset-3">
                <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
            </div>
        </div>
    </form>
@endsection