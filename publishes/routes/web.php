<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Avatar
Route::get('avatar/{id}/{size?}', function ($id, $size = 128) {
    return Avatar::response($id, $size);
})
    ->where('id', '[0-9]+')
    ->where('size', '[0-9]*');

//Sitemap
Route::get('sitemap{page?}.xml', 'SitemapController@index')->where('page', '[0-9]*');

//Guest
Route::group(['middleware' => 'guest'], function () {
    Route::get('login', 'AuthController@getLogin')->name('login');
    Route::post('login', 'AuthController@postLogin');
    Route::get('register', 'AuthController@getRegister');
    Route::post('register', 'AuthController@postRegister');
    Route::get('forgot-password', 'AuthController@getForgotPassword');
    Route::post('forgot-password', 'AuthController@postForgotPassword');
    Route::get('reset-password/{token?}', 'AuthController@getResetPassword')->name('password.reset');
    Route::post('reset-password', 'AuthController@postResetPassword');
    Route::get('{provider}/login', 'AuthController@getSocialiteLogin');
    Route::get('{provider}/authorize', 'AuthController@getSocialiteAuthorize');
});

//Auth
Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', 'AuthController@getLogout');
    Route::get(config('site.admin_path'), ['middleware' => 'permission:dashboard', 'uses' => 'AdminController@layout']);
    //My
    Route::group(['prefix' => 'my'], function () {
        Route::get('', 'MyController@getProfile');
        Route::post('', 'MyController@postProfile');
        Route::get('setting', 'MyController@getSetting');
        Route::post('setting', 'MyController@postSetting');
        Route::post('password', 'MyController@postPassword');
        Route::post('reset-api-token', 'MyController@postResetApiToken');
    });
});

Route::get('', 'HomeController@index')->name('home');
Route::get(config('site.uri.post'), 'CastController@castPost')
    ->where('slug', '[a-z0-9-]+')
    ->where('id', '[0-9]+');

Route::get(config('site.uri.category') . '/{page?}', 'CastController@castCategory')
    ->where('slug', '[a-z0-9-]+')
    ->where('id', '[0-9]+')
    ->where('page', '[0-9]+');
