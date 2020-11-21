<?php

namespace App\Http\Controllers\Api\Admin\Auth;

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
    |         Login Admin 
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {  
         $credentials = $request->only('email','password');
         $LoginValidate = $this->LoginValidate($request);
         if($LoginValidate){
             return $LoginValidate;
         } 
        try {
            //login
            if($token = auth()->guard('admins')->attempt($credentials)){
                 $admin = Auth::guard('admins')->user();
                
                $admin->access_token = $token;
                $email = $request->email;
               // $this->RegisterDevice( $email,$request->deviceToken);
              return $this->returnData('admin',$admin,trans('response_msg.login_success'));
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
    |         To Validate Login
    |--------------------------------------------------------------------------
    */
    public  function LoginValidate($request)
    {
        $rules = [
            "email"         => 'required|email',
            "password"      => 'required',
        // "deviceToken"    => 'required|string' 
        ];
        $messages = [
            'email'         => trans('validation.email'),
            'password'      => trans('validation.password')       
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
