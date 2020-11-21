<?php

namespace App\Http\Controllers\Api\Consultant\Auth;

use App\Http\Controllers\Controller;
use App\Models\Consultant;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

                $consultant= Consultant::create([
                    'username'          => $request->username,
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'password'          => bcrypt($request->password),
                    'mobile'            => $request->mobile,
                    'company_id'        => $request->company_id,
                ]);
                $access_token = JWTAuth::fromUser($consultant);
                $consultant->access_token = $access_token;
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
            return $this->returnData('consultant',$consultant,trans('response_msg.register'));
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public  function registerValidate($request)
    {
        $rules = [
            'username'          => 'required|regex:/^\S*$/u|unique:consultants|max:255|min:3',
            'name'              => 'required|string',
            'password'          => 'required|string|min:8',
            'email'             => 'required||email|unique:consultants',
            'mobile'            => 'required|unique:consultants|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            //'company_id'        => 'required|exists:companies,id',
        ];
        $messages = [
            'email'             => trans('validation.email'),
            'password.'         => trans('validation.password'),
            'username'          => trans('validation.username'),
            'mobile'            => trans('validation.mobile'),
            'name'              => trans('validation.name'),
            'company_id'        => trans('validation.company_id'),        
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
