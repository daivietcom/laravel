<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1', 'middleware' => 'permission:dashboard'], function () {
    //Cpanel
    Route::get('dashboard', 'Backend\DashboardController@index');
    //Cache
    Route::group(['prefix' => 'cache'], function () {
        Route::post('clear/{key}', 'Backend\CacheController@postClear');
    });
    //Config
    Route::group(['prefix' => 'config', 'middleware' => 'permission:config'], function () {
        Route::get('', 'Backend\ConfigController@jsonConfig');
        Route::post('', 'Backend\ConfigController@postConfig');
    });
    //User
    Route::group(['middleware' => 'permission:user'], function () {
        Route::post('user/avatar', 'Backend\UserController@postAvatar');
        Route::get('user/query/{query?}', 'Backend\UserController@getQuery');
        Route::resource('user', 'Backend\UserController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    });
    //Group
    Route::group(['middleware' => 'permission:group'], function () {
        Route::get('group/all', 'Backend\GroupController@getAll');
        Route::get('group/permissions/{query?}', 'Backend\GroupController@getQuery');
        Route::resource('group', 'Backend\GroupController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
    });
    //Tag
    Route::group(['prefix' => 'tag', 'middleware' => 'permission:tag'], function () {
        Route::get('query/{query?}', 'Backend\TagController@getQuery');
    });
    // Media
    Route::group(['prefix' => 'media'], function () {
        Route::post('', ['middleware' => 'permission:media', 'uses' => 'Backend\MediaController@store']);
        Route::post('upload', ['middleware' => 'permission:media.upload', 'uses' => 'Backend\MediaController@postUpload']);
        Route::post('delete/{id}', ['middleware' => 'permission:media.delete', 'uses' => 'Backend\MediaController@postDelete']);
        Route::post('deletes', ['middleware' => 'permission:media.delete', 'uses' => 'Backend\MediaController@postDeletes']);
        Route::post('rename', ['middleware' => 'permission:media.rename', 'uses' => 'Backend\MediaController@postRename']);
        Route::post('move', ['middleware' => 'permission:media.move', 'uses' => 'Backend\MediaController@postMove']);
        Route::post('folder', ['middleware' => 'permission:media.folder', 'uses' => 'Backend\MediaController@postCreateFolder']);
        Route::get('{folder}', ['middleware' => 'permission:media.view', 'uses' => 'Backend\MediaController@jsonMedia'])
            ->where('folder', '[0-9]+')
        ;
    });
    //Module
    Route::group(['middleware' => 'permission:module'], function () {
        Route::post('module/check', 'Backend\ModuleController@check');
        Route::post('module/download', 'Backend\ModuleController@download');
        Route::post('module/unpack', 'Backend\ModuleController@unpack');
        Route::resource('module', 'Backend\ModuleController', ['only' => ['index', 'store', 'update', 'destroy']]);
    });
    //Theme
    Route::group(['middleware' => 'permission:theme'], function () {
        Route::get('theme/gadget', 'Backend\ThemeController@getGadget');
        Route::post('theme/gadget', 'Backend\ThemeController@postGadget');
        Route::post('theme/check', 'Backend\ThemeController@check');
        Route::post('theme/download', 'Backend\ThemeController@download');
        Route::post('theme/unpack', 'Backend\ThemeController@unpack');
        Route::resource('theme', 'Backend\ThemeController', ['only' => ['index', 'store', 'update', 'destroy']]);
    });
});
