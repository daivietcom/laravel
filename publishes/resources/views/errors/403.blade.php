@extends('errors.layout')
@section('title', __('Error 403: Forbidden'))
@section('message', __('Access is denied!'))
@section('statusCode', 403)