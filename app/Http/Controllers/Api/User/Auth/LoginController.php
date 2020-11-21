<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Support\Facades\App;

class LoginController extends Controller
{ 
    use GeneralTrait;
    /*
    |--------------------------------------------------------------------------
    |         Login User 
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {  
        try {
            $LoginValidate = $this->LoginValidate($request);
            if($LoginValidate){
                return $LoginValidate;
            } 
            $identify= $this->username($request->identify);
            $credentials = $request->only($identify,'password');
            if($token = auth()->guard('users')->attempt($credentials)){
                 $user = Auth::guard('users')->user();
                 if($user->block == 1){
                    return redirect()->route('isBlocked');
                 }
                $user->access_token = $token;
                $email=$request->email;
               // $this->RegisterDevice( $email,$request->deviceToken);
              return $this->returnData('user',$user,trans('response_msg.login_success'));
               
             }
             else
             return $this->returnError('E001',trans('response_msg.login_err'));
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }
    /*
    |--------------------------------------------------------------------------
    |         Logout User 
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {  
        $token = $request->header( 'Authorization' );
        try {
            JWTAuth::parseToken()->invalidate( $token );
            return $this->returnSuccessMessage(trans('response_msg.logout'),'S000');
          
        } catch ( TokenExpiredException $exception ) {
            return $this->returnError('T001',trans('response_msg.token_expired'));

        } catch ( TokenInvalidException $exception ) {
            return $this->returnError('T002', trans('response_msg.token_invalid'));

        } catch ( JWTException $exception ) {
            return $this->returnError('T003', trans('response_msg.token_notFound'));
        }
    }
    /*
    |--------------------------------------------------------------------------
    |         To Get User Username Or Mobile Number  
    |--------------------------------------------------------------------------
    */
    public function username($identify)
    {
        $value  =   $identify;
        $felid  =   filter_var($value,FILTER_SANITIZE_NUMBER_INT) ? 'mobile' :'username';
        request()->merge([$felid => $value]);
        return $felid;
    }
     /*
    |--------------------------------------------------------------------------
    |         To Validate Login
    |--------------------------------------------------------------------------
    */
    public  function LoginValidate($request)
    {
        $rules = [
            $this->username($request->identify)     =>'required',
            'password'                              =>'required', 
            //'deviceToken'                         => 'required|string' 
        ];
        $messages = [
            $this->username($request->identify)     => trans('validation.identify'),
            'password'                              => trans('validation.password'),
            //'deviceToken'                         => trans('validation.deviceToken'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
