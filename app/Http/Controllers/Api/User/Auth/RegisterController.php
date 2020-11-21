<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
class RegisterController extends Controller
{
    use GeneralTrait;
    public function signup(Request $request)
    { 
        try{
            $registerValidation =$this->registerValidate($request);
            if( $registerValidation){
                return $registerValidation;
            } 
                $user= User::create([
                    'username'          => $request->username,
                    'email'             => $request->email,
                    'password'          => bcrypt($request->password),
                    'mobile'            => $request->mobile,
                    'email_verified'    => 1 ,

                ]);
                $access_token = JWTAuth::fromUser($user);
                $user->access_token = $access_token;
                // $name       = $request->name;
                // $email      = $request->email;
                // $user_id    = $user->id;
                //$this->sendActivationCode($name,$email,$user_id);
                //$email=$request->get('email');
                // $this->RegisterDevice( $email,$request->deviceToken);   
                // $registrationIdsUsers =$this->getUserTokens($userId);
                // $UserTitle=" إشعار جديد";
                // $UserMessage="يا مرحبتين نور التطبيق";
                // $USER_API_KEY=env('USER_API_KEY');
                //  $this->send_notification($registrationIdsUsers,$UserTitle,$UserMessage,$USER_API_KEY);
            return $this->returnData('user',$user,trans('response_msg.register'));
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public  function registerValidate($request)
    {
        $rules = [
            'username'          => 'required|regex:/^\S*$/u|unique:users|max:255|min:3',
            'password'          => 'required|string|min:8',
            'email'             => 'required||email|unique:users',
            'mobile'            => 'required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
        $messages = [
            'email'             => trans('validation.email'),
            'password.'         => trans('validation.password'),
            'username'          => trans('validation.username'),
            'mobile'            => trans('validation.mobile'),    
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
