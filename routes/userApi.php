<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  /*
    |--------------------------------------------------------------------------
    |        for User App Auth
    |--------------------------------------------------------------------------
    */
    Route::group([ 'middleware' =>['api','cors',],'namespace' =>'Api\User'], function () {
        Route::group(['namespace' =>'Auth','prefix' => 'auth'], function () {
            Route::post('signup'                , 'RegisterController@signup');
            Route::post('login'                 , 'LoginController@login');  
            Route::post('send-code'             , 'ForgetPasswordController@sendOtp');
            Route::post('resend-code'           , 'ForgetPasswordController@sendOtp');
            Route::post('code-check'            , 'ForgetPasswordController@checkOtp');
            Route::post('reset-password'         , 'ResetPasswordController@resetPassword');
            //Route::post('getAuthenticatedUser'  , 'UserController@getAuthenticatedUser');
            //Route::get('not-active'             , 'UserController@notActive')->name('notActive');
        });
        Route::group(['prefix' => 'auth'], function () {
            Route::get('not-active'             , 'UserController@notActive')->name('notActive');
            Route::get('is-blocked'             , 'UserController@isBlocked')->name('isBlocked');
            Route::get('not-user'               , 'UserController@notUser')->name('user_permission');
        });
    });
   

    Route::group([ 'middleware' =>['api','cors','is_user','is_blocked'],'namespace' =>'Api\User'], function () {
        /*
        |--------------------------------------------------------------------------
        |  For User Edit Profile and Logout and Get User By Token
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'profile'], function () {
            Route::post('update'   , 'UserController@update');
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
       
         /*
        |--------------------------------------------------------------------------
        |  For User   consultant
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'consultant'], function () {
            Route::get('get-all-consultant'               , 'ConsultantController@GetAllConsultant');
            Route::get('get-consultant'                   , 'ConsultantController@GetConsultant');
            Route::post('store'                           , 'ConsultantRateController@store');
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


 