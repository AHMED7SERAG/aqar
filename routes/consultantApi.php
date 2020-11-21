<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
    /*
    |--------------------------------------------------------------------------
    |        for Consultant App Auth
    |--------------------------------------------------------------------------
    */
    Route::group([ 'middleware' =>['api','cors',],'namespace' =>'Api\Consultant'], function () {
        Route::group(['namespace' =>'Auth','prefix' => 'auth'], function () {
            Route::post('signup'                , 'RegisterController@signup');
            Route::post('login'                 , 'LoginController@login');  
            Route::post('send-code'             , 'ForgetPasswordController@sendOtp');
            Route::post('resend-code'           , 'ForgetPasswordController@sendOtp');
            Route::post('code-check'            , 'ForgetPasswordController@checkOtp');
            Route::post('reset-password'        , 'ResetPasswordController@resetPassword');
        });
    });
   

    Route::group([ 'middleware' =>['api','cors','is_consultant'],'namespace' =>'Api\Consultant'], function () {
        /*
        |--------------------------------------------------------------------------
        |  For User Edit Profile and Logout and Get User By Token
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'profile'], function () {
            Route::post('update'   , 'ConsultantController@update');
            Route::post('logout'   , 'Auth\LoginController@logout');
        });
        /*
        |--------------------------------------------------------------------------
        |  For User Added New Aqar  
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'aqar'], function () {
            Route::get('get-lands'              , 'AqarController@getLands');
            Route::get('get-home'              , 'AqarController@getHomePage');
            Route::get('get-aqar'               , 'AqarController@getAqar');
            Route::post('store'                 , 'AqarController@store');
            Route::post('update'                , 'AqarController@update');
            Route::post('delete'                , 'AqarController@destroy');
        });
        /*
        |--------------------------------------------------------------------------
        |  For User Send New Maintenance Request
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'maintenance'], function () {
            Route::get('get-user-maintenance-request'     , 'MaintenanceController@getUserMaintenance');
            Route::post('store'                           , 'MaintenanceController@store');
            Route::post('update'                          , 'MaintenanceController@update');
            Route::post('delete'                          , 'MaintenanceController@destroy');
        });
         /*
        |--------------------------------------------------------------------------
        |  For User Send New Maintenance Request
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'finance'], function () {
            Route::get('get-user-finance-application'     , 'FinanceApplicationController@getUserFinanceApplication');
            Route::get('string'                           , 'FinanceApplicationController@stringToArry');
            Route::post('store'                           , 'FinanceApplicationController@store');
            Route::post('update'                          , 'FinanceApplicationController@update');
            Route::post('delete'                          , 'FinanceApplicationController@destroy');
        });

         /*
        |--------------------------------------------------------------------------
        |  For Admin Add App Setting
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'setting'], function () {
            Route::get('get-app-setting'        , 'AppSettingController@getAppSetting');
        });
        /*
        |--------------------------------------------------------------------------
        |  For Admin Add App Social
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'social'], function () {
            Route::get('get-app-social'         , 'AppSocialController@getAppSocial');
        });
          /*
        |--------------------------------------------------------------------------
        |  For User Send Contact Message
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'contact'], function () {
            Route::post('store'                           , 'ContactController@store');
            Route::post('update'                          , 'ContactController@update');
            Route::post('delete'                          , 'ContactController@destroy');
        });
      
    });
    /*
    |--------------------------------------------------------------------------
    |   for User App Operation
    |--------------------------------------------------------------------------
    */
    Route::group([ 'middleware' =>['api','cors' ,'is_user','is_active','is_blocked'],'namespace' =>'Api\User'], function () {
        /*
        |--------------------------------------------------------------------------
        |  For User Edit Profile and Logout and Get User By Token
        |--------------------------------------------------------------------------
        */
     
       
    });


 