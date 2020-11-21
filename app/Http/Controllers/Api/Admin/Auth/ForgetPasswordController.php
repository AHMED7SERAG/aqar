<?php

namespace App\Http\Controllers\Api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Otp;
use App\Notifications\SendOtpRestPasswordNotification;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    use GeneralTrait;
      /*
    |--------------------------------------------------------------------------
    |         To Send  OTP Code To Email
    |--------------------------------------------------------------------------
    */
    public function sendOtp(Request $request)
    {
        try{
            $ResetValidate = $this->ResetValidate($request);
            if($ResetValidate){
                return $ResetValidate;
            } 
            $user = Otp::select('email')->where('email', $request->email)->orderBy('id', 'desc')->first();
            if(!$user){
            try{
                    $userInfo  = Admin::where('email' , $request->email)->first();
                    $userEmail = Admin::select('email')->where('email', $request->email)->first();
                    $otp       = $this->generateOtp(); 
                    $details   = [
                        'greeting'  => trans('response_msg.greeting') . ' '. $userInfo['name'],
                        'body'      => trans('response_msg.body').' : ' .$otp,
                        'thanks'    => trans('response_msg.thanks'),
                    ]; 
                    Notification::send($userEmail , new SendOtpRestPasswordNotification($details));
                    Otp::create([
                        'email'         => $request->email,
                        'code'          => $otp,
                        'receiver_id'   => $userInfo['id']
                    ]);
                    return $this->returnSuccessMessage(trans('response_msg.successOtp'),'S000');                     
                }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
                }

            }
            $userDetails  = Admin::select('name')->where('email' , $request->email)->first();
            $emailDetails = Admin::select('email')->where('email', $request->email)->first();
            $old_otp = Otp::select('code')->where('email', $request->email)->orderBy('id', 'desc')->first();
            $details = [
                'greeting'  => trans('response_msg.greeting').  ' '.$userDetails['name'],
                'body'      => trans('response_msg.body').' : ' . $old_otp['code'],
                'thanks'    => trans('response_msg.thanks'),
            ]; 
            Notification::send($emailDetails, new SendOtpRestPasswordNotification($details));
            return $this->returnSuccessMessage(trans('response_msg.successOtp'),'S000');                     
        }catch (\Exception $ex){
        return $this->returnError($ex->getCode(), $ex->getMessage());
        }

     }

    /*
    |--------------------------------------------------------------------------
    |         To Check OTP Code  
    |--------------------------------------------------------------------------
    */
     public function checkOtp(Request $request)
     {
         try{
                $CheckOTPValidate = $this->CheckOTPValidate($request);
                if($CheckOTPValidate){
                    return $CheckOTPValidate;
                } 
                $otp = Otp::select('code')->where('email',$request->email)->orderBy('id', 'desc')->first();
                if(!$otp){
                    return $this->returnError('001',trans('response_msg.OtpNotFoundInDb'));
                }
                if($otp['code'] == $request->code){
                    $otp_id = Otp::select('id')->where('email',$request->email)->first();
                    if(!$otp_id){
                        return $this->returnError('001',trans('response_msg.errorOtp'));
                    }
                    $otp_record =   otp::find($otp_id['id']);
                    $user   =   Admin::where('email',$request->email)->first();
                    $otp_record->delete();  
                    return $this->returnSuccessMessage(trans('response_msg.successOtpCheck'),'S000');                     
                }
                    return $this->returnError('001',trans('response_msg.errorOtpCheck'));
             }catch (\Exception $ex){
                 return $this->returnError($ex->getCode(), $ex->getMessage());
             }
     }
    
     /*
    |--------------------------------------------------------------------------
    |         To Validate Reset
    |--------------------------------------------------------------------------
    */
    public  function ResetValidate($request)
    {
        $rules = [
            'email'               => 'required|email|exists:admins,email'
        ];              
        $messages = [
            'email'               => trans('validation.email'),
        ];
            $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
               return $this->returnValidationError($code, $validator);
        }
    } 
     /*
    |--------------------------------------------------------------------------
    |         To Validate Check OTP
    |--------------------------------------------------------------------------
    */
     public  function CheckOTPValidate($request)
    {
        $rules = [
            'code'         => 'required|numeric',
            'email'        => 'required|email|exists:admins,email'
        ];
        $messages = [
           'code'          =>trans('validation.code'),
           'email'         =>trans('validation.email'), 
        ];
        $validator = Validator::make($request->all(), $rules, $messages );
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
         }
    }
}
