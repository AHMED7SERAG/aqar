<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    use GeneralTrait;
    /*
    |--------------------------------------------------------------------------
    |         To Store New Password
    |--------------------------------------------------------------------------
    */
    public function resetPassword(Request $request)
    { 
        try{
            $NewPasswordValidate =$this->NewPasswordValidate($request);
            if( $NewPasswordValidate){
                return $NewPasswordValidate;
            } 
            $user_id =  User::select('id')->where('email',$request->email)->first();
            $user = User::find($user_id['id']);
            if(!$user){
                return $this->returnError('001',trans('response_msg.user_id_err'));
            }
            if($request->password!==$request->password_confirmation){
                return $this->returnError('001',trans('response_msg.password_confirm_err'));
            }
            $user->update([
             'password' => bcrypt($request->password),
            ]);
            return $this->returnData('user',$user,trans('response_msg.change_password'));
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }

    }
    /*
    |--------------------------------------------------------------------------
    |         To Validate New Password
    |--------------------------------------------------------------------------
    */
    public  function NewPasswordValidate($request)
    {
        $rules = [
            'email'         => 'required|email|exists:users,email',
            'password'      => 'required|confirmed|min:8',
        ];
         $messages = [
                'email'     =>trans('validation.email'),
                'password'  => trans('validation.password')
        ];
            $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
