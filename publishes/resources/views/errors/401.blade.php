@extends('errors.layout')
@section('title', __('Error 401: Unauthorized'))
@section('message', __('Access is denied!'))
@section('statusCode', 401)