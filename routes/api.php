<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API For User  App
|--------------------------------------------------------------------------
*/

  /*
    |--------------------------------------------------------------------------
    |        For Crud Beautician 
    |--------------------------------------------------------------------------
    */
    // Route::group([ 'middleware' =>['api','cors','jwt.verify','is_user','is_active','is_blocked'],'prefix' => 'users','namespace' =>'Api'], function () {
    //     Route::group(['prefix' => 'rates' , 'namespace' =>'Beaut'], function () {
    //         Route::get('get-all-beautician-rate'    , 'BeauticianRateController@GetAllBeauticianRate');
    //         Route::get('get-user-beautician-rate'   , 'BeauticianRateController@GetAllBeauticianRateForUser');
    //         Route::post('store'                     , 'BeauticianRateController@store');
    //         Route::put('update'                     , 'BeauticianRateController@update');
    //         Route::delete('delete'                  , 'BeauticianRateController@destroy');
    //     });
    // });
 /*
    |--------------------------------------------------------------------------
    | API For  User  Operation   
    |--------------------------------------------------------------------------
    */

  /*
    |--------------------------------------------------------------------------
    |        For Other User  Namespace  Beautician
    |--------------------------------------------------------------------------
    */
    // Route::group([ 'middleware' =>['api','cors','jwt.verify','is_user','is_active','is_blocked'],'prefix' => 'user','namespace' =>'Api'], function () {
        /*
        |--------------------------------------------------------------------------
        |  For User to get  Beautician
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'provider','namespace' =>'Beaut'], function () {
        //     Route::get('get-all-provider'      , 'BeauticianController@GetAllProvider');

        // });
        /*
        |--------------------------------------------------------------------------
        |  For User to get  Categories
        |--------------------------------------------------------------------------
    //     */
    //     Route::group(['namespace' =>'Admin','prefix' => 'categories'], function () {
    //         Route::get('get-all-categories'  , 'CategoryController@GetAllCategory');
    //     });
    // });
    
    /*
    |--------------------------------------------------------------------------
    |   for User App Operation
    |--------------------------------------------------------------------------
    */
    // Route::group([ 'middleware' =>['api','cors' ,'jwt.verify'],'prefix' => 'users','namespace' =>'Api\User'], function () {
        /*
        |--------------------------------------------------------------------------
        |  For User Edit Profile and Logout and Get User By Token
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'user'], function () {
        //     Route::post('get-user' , 'UserController@getUser');
        //     Route::put('update'    , 'UserController@update');
        //     Route::post('logout'   , 'UserController@logout');
        // });
         /*
        |--------------------------------------------------------------------------
        |  For User to Mange His Locations  
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'locations'], function () {
        //     Route::get('get-my-locations' , 'UserLocationController@GetMyLocations');
        //     Route::post('store'            , 'UserLocationController@store');
        //     Route::put('update'           , 'UserLocationController@update');
        //     Route::delete('destroy'          , 'UserLocationController@destroy');
        // });
        /*
        |--------------------------------------------------------------------------
        |  For User to Mange His Card  
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'cards'], function () {
        //     Route::get('get-my-cards'    , 'CardController@GetMyCards');
        //     Route::post('store'          , 'CardController@store');
        //     Route::put('update'        , 'CardController@update');
        //     Route::delete('destroy'      , 'CardController@destroy');
        // });
         /*
        |--------------------------------------------------------------------------
        |  For User to Mange His Payment Method  
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'methods'], function () {
        //     Route::get('get-payment-method-card'    , 'PaymentMethodController@GetPaymentMethodWithMyCards');
        //     Route::post('store'                     , 'PaymentMethodController@store');
        //     Route::put('update'                     , 'PaymentMethodController@update');
        //     Route::delete('destroy'                 , 'PaymentMethodController@destroy');
        // });

          /*
        |--------------------------------------------------------------------------
        |  For User to Mange His Orders 
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'orders'], function () {
        //     Route::get('get-my-orders'          , 'OrderController@GetMyOrders');
        //     Route::get('get-current-orders'     , 'OrderController@GetUserCurrentOrders');
        //     Route::get('get-previous-orders'    , 'OrderController@GetUserPreviousOrders');
        //     Route::post('store'                 , 'OrderController@store');
        //     Route::put('update'                 , 'OrderController@update');
        //     Route::delete('destroy'             , 'OrderController@destroy');
        // });
        /*
        |--------------------------------------------------------------------------
        |  For User to Mange Search Functionality Orders 
        |--------------------------------------------------------------------------
        */
    //     Route::group(['prefix' => 'search'], function () {
    //         Route::get('category-search'            , 'SearchController@searchByCategory');
    //         Route::get('search-beautician-name'     , 'SearchController@searchByBeauticianName');
    //         Route::get('search-beautician-address'  , 'SearchController@searchByBeauticianAddress');
    //         Route::get('search-beautician-time'     , 'SearchController@searchByBeauticianTime');
    //     });
    // });


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
    // Route::group([ 'middleware' =>['api','cors'],'prefix' => 'admins','namespace' =>'Api\Admin'], function () {
    //     Route::group(['prefix' => 'auth'], function () {
    //         Route::post('signup'                , 'AdminController@store');
    //         Route::post('login'                 , 'AdminController@login');
    //         Route::post('code-check'            , 'AdminController@checkOtp');
    //         Route::post('send-code'             , 'AdminController@sendOtp');
    //         Route::post('resend-code'           , 'AdminController@sendOtp');
    //         Route::put('reset-password'         , 'AdminController@resetPassword');
    //         Route::post('getAuthenticatedUser'  , 'AdminController@getAuthenticatedUser');
    //         Route::get('not-admin'             , 'AdminController@notAdmin')->name('admin_permission');

    //     });
    // });
    /*
    |--------------------------------------------------------------------------
    |   For Admin  DashBoard Operation ge out side function
    |--------------------------------------------------------------------------
    */
    // Route::group(['prefix' => 'cities'], function () {
    //     Route::get('get-all-cities'     , 'Api\Admin\CityController@GetAllCities');
    //   });
    //   Route::group(['prefix' => 'methods'], function () {
    //     Route::get('get-all-methods'     , 'Api\User\PaymentMethodController@GetAllMethods');
    //   });
    // Route::group([ 'middleware' =>['api','cors' ,'jwt.verify'],'prefix' => 'admins','namespace' =>'Api\Admin'], function () {
        /*
        |--------------------------------------------------------------------------
        |  For Admin Edit Profile and Logout and Get Admin By Token
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'profile'], function () {
        //     Route::put('update'    , 'AdminController@update');
        //     Route::post('logout'   , 'AdminController@logout');
        // });
         /*
        |--------------------------------------------------------------------------
        |  For Admin to Mange Categories
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'categories'], function () {
        //     Route::get('get-all-categories'  , 'CategoryController@GetAllCategory');
        //     Route::post('store'             , 'CategoryController@store');
        //     Route::put('update'             , 'CategoryController@update');
        //     Route::delete('destroy'          , 'CategoryController@destroy');
        // });
        /*
        |--------------------------------------------------------------------------
        |  For Admin to Mange  Cities  
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'cities'], function () {
        //     Route::get('get-all-cities'     , 'CityController@GetAllCities');
        //     Route::post('store'             , 'CityController@store');
        //     Route::put('update'             , 'CityController@update');
        //     Route::delete('destroy'         , 'CityController@destroy');
        // });
         /*
        |--------------------------------------------------------------------------
        |  For Admin to Mange Coupons  
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'coupons'], function () {
        //     Route::get('get-all-coupons'    , 'CouponController@GetAllCoupons');
        //     Route::post('store'             , 'CouponController@store');
        //     Route::put('update'             , 'CouponController@update');
        //     Route::delete('destroy'         , 'CouponController@destroy');
        // });
            /*
        |--------------------------------------------------------------------------
        |  For Admin to Mange Services  
        |--------------------------------------------------------------------------
        */
    //     Route::group(['prefix' => 'services'], function () {
    //         Route::get('get-all-services'   ,'ServiceController@GetAllServices');
    //         Route::post('store'             , 'ServiceController@store');
    //         Route::put('update'             , 'ServiceController@update');
    //         Route::delete('destroy'         , 'ServiceController@destroy');
    //     });
       
    // });
   /*
    |--------------------------------------------------------------------------
    | API For Admin  Beautician  Operation   
    |--------------------------------------------------------------------------
    */

  /*
    |--------------------------------------------------------------------------
    |        For Other Namespace  
    |--------------------------------------------------------------------------
    */
    // Route::group([ 'middleware' =>['api','cors','is_admin'],'prefix' => 'admins','namespace' =>'Api'], function () {
    //     Route::group(['prefix' => 'provider','namespace' =>'Beaut'], function () {
    //         Route::get('get-all-provider'      , 'BeauticianController@GetAllProvider');
    //         Route::put('update'                , 'BeauticianController@update');
    //         Route::delete('delete'             , 'BeauticianController@destroy');

    //     });
    //     Route::group(['prefix' => 'rates','namespace' =>'Beaut'], function () {
    //         Route::get('get-all-rates'      , 'BeauticianRateController@GetAllBeauticianRate');
    //         Route::delete('delete'          , 'BeauticianRateController@destroy');
    //     });
    //     Route::group(['prefix' => 'methods'], function () {
    //         Route::get('get-all-payment-method'    , 'PaymentMethodController@GetAllMethods');
    //         Route::post('store'                     , 'PaymentMethodController@store');
    //         Route::put('update'                   , 'PaymentMethodController@update');
    //         Route::delete('destroy'                 , 'PaymentMethodController@destroy');
    //     });
    //     Route::group(['prefix' => 'users','namespace' =>'User'], function () {
    //         Route::get('get-all-users'      , 'UserController@GetAllUsers');
    //         Route::post('store'             , 'UserController@store');
    //         Route::put('update'             , 'UserController@update');
    //         Route::delete('delete'          , 'UserController@destroy');

    //     });
    //     Route::group(['prefix' => 'orders','namespace' =>'User'], function () {
    //         Route::get('get-completed-orders'    , 'OrderController@GetListOfOrders');
    //         Route::get('get-all-orders'         , 'OrderController@GetListOfAllOrders');
    //         Route::put('update'                  , 'OrderController@update');
    //         Route::delete('delete'               , 'OrderController@destroy');

    //     });
    // });
      /*
    |--------------------------------------------------------------------------
    | API For Beautician  App 
    |--------------------------------------------------------------------------
    */

  /*
    |--------------------------------------------------------------------------
    |        for Beautician  App Auth
    |--------------------------------------------------------------------------
    */
    // Route::group([ 'middleware' =>['api','cors'],'prefix' => 'beautician','namespace' =>'Api\Beaut'], function () {
    //     Route::group(['prefix' => 'auth'], function () {
    //         Route::post('signup'                , 'BeauticianController@store');
    //         Route::post('login'                 , 'BeauticianController@login');
    //         Route::post('code-check'            , 'BeauticianController@checkOtp');
    //         Route::post('send-code'             , 'BeauticianController@sendOtp');
    //         Route::post('resend-code'           , 'BeauticianController@sendOtp');
    //         Route::put('reset-password'         , 'BeauticianController@resetPassword');
    //         Route::post('getAuthenticatedUser'  , 'BeauticianController@getAuthenticatedUser');
    //         Route::get('not-active'              ,'BeauticianController@BeautNotActive')->name('BeautNotActive');
    //         Route::get('is-blocked'              ,'BeauticianController@isBeautBlocked')->name('BlockedBeautician');

    //     });
    // });

     /*
    |--------------------------------------------------------------------------
    |   For Admin  DashBoard Operation
    |--------------------------------------------------------------------------
    */
    // Route::group([ 'middleware' =>['api','cors' ,'jwt.verify','is_beaut','is_active_beautician','is_blocked_beautician'],
    // 'prefix' => 'beautician','namespace' =>'Api\Beaut'], function () {
        /*
        |--------------------------------------------------------------------------
        |  For Beaut Edit Profile and Logout and Get Beaut By Token
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'profile'], function () {
        //     Route::put('update'        , 'BeauticianController@update');
        //     Route::delete('destroy'    , 'BeauticianController@destroy');
        //     Route::post('logout'       , 'BeauticianController@logout');
        // });
        /*
        |--------------------------------------------------------------------------
        |  For Beautician to Mange Services  
        |--------------------------------------------------------------------------
        */
        // Route::group(['prefix' => 'services'], function () {
        //     Route::get('get-all-services'   , 'ServiceController@GetAllServices');
        //     Route::post('store'             , 'ServiceController@store');
        //     Route::put('update'             , 'ServiceController@update');
        //     Route::delete('destroy'         , 'ServiceController@destroy');
        // });
         /*
        |--------------------------------------------------------------------------
        |  For Beautician to Mange Gallery  
        |--------------------------------------------------------------------------
        */
    //     Route::group(['prefix' => 'gallery'], function () {
    //         Route::get('get-all-gallery'    , 'GalleryController@GetBeauticianGallery');
    //         Route::post('store'             , 'GalleryController@store');
    //         Route::put('update'             , 'GalleryController@update');
    //         Route::delete('destroy'         , 'GalleryController@destroy');
    //         Route::delete('delete-photo'    , 'GalleryController@deleteOnePhoto');
    //     });
       
    // });

     /*
    |--------------------------------------------------------------------------
    | API For  Beautician  Operation   
    |--------------------------------------------------------------------------
    */

  /*
    |--------------------------------------------------------------------------
    |        For Other Beaut  Namespace  Beautician
    |--------------------------------------------------------------------------
    */
    // Route::group([ 'middleware' =>['api','cors','jwt.verify','is_beaut','is_active_beautician','is_blocked_beautician'],'prefix' => 'beautician','namespace' =>'Api'], function () {
    //     Route::group(['prefix' => 'rates','namespace' =>'Beaut'], function () {
    //         Route::get('get-all-rates'      , 'BeauticianRateController@GetAllBeauticianRate');
    //         Route::delete('delete'          , 'BeauticianRateController@destroy');
    //     });

    // });