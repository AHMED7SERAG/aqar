<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | API For Admin  DashBoard Operation
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    |        for Admin  DashBoard Auth
    |--------------------------------------------------------------------------
    */
    Route::group([ 'middleware' =>['api','cors'],'namespace' =>'Api\Admin'], function () {
        Route::group(['namespace' =>'Auth','prefix' => 'auth'], function () {
            Route::post('login'                 , 'LoginController@login');  
            Route::post('send-code'             , 'ForgetPasswordController@sendOtp');
            Route::post('resend-code'           , 'ForgetPasswordController@sendOtp');
            Route::post('code-check'            , 'ForgetPasswordController@checkOtp');
            Route::post('reset-password'         , 'ResetPasswordController@resetPassword');
        });
        Route::group(['prefix' => 'auth'], function () {
            Route::get('not-admin'             , 'AdminController@notAdmin')->name('admin_permission');

        });
    });


    Route::group([ 'middleware' =>['api','cors','is_admin'],'namespace' =>'Api\Admin'], function () {
        /*
        |--------------------------------------------------------------------------
        |  For Admin Edit Profile and Logout and Get User By Token
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'profile'], function () {
            Route::post('update'   , 'AdminController@update');
            Route::post('logout'   , 'Auth\LoginController@logout');
        });
        /*
        |--------------------------------------------------------------------------
        |  For Admin Add App Setting
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'setting'], function () {
            Route::get('get-app-setting'        , 'AppSettingController@getAppSetting');
            Route::post('store'                 , 'AppSettingController@store');
            Route::post('update'                , 'AppSettingController@update');
            Route::post('delete'                , 'AppSettingController@destroy');
        });
        /*
        |--------------------------------------------------------------------------
        |  For Admin Add App Social
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'social'], function () {
            Route::get('get-app-social'         , 'AppSocialController@getAppSocial');
            Route::post('store'                 , 'AppSocialController@store');
            Route::post('update'                , 'AppSocialController@update');
            Route::post('delete'                , 'AppSocialController@destroy');
        });
      
    });
    