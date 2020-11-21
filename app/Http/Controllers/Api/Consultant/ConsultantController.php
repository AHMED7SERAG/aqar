<?php

namespace App\Http\Controllers\Api\Consultant;

use App\Http\Controllers\Controller;
use App\Models\Consultant;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class ConsultantController extends Controller
{
    use GeneralTrait;
    private $lang;   
    public function __construct(Request $request)
   {
       App::setlocale($request->lang);
       $this->lang= app()->getLocale();
   }
    public function GetAllUsers(Request $request)
    {
        try
        {
            $users     = Consultant::with('locations')->get();
            if(count($users) < 1){
                return $this->returnError('E002',trans('response_msg.users_err') );
            }
                return $this->returnData('users',$users,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function update(Request $request)
    {        
        try{  
            $UpdateValidate = $this->UpdateValidate($request);
            if($UpdateValidate){
                return $UpdateValidate;
            }
           if($request->has('consultant_id')){
            $consultant_id    =  $request->consultant_id;
           }else{
            $userAuth   = Auth::guard('consultants')->user();
            if(!isset( $userAuth->id )){
                return $this->returnError('001',trans('response_msg.user_id_err'));
              }
            $consultant_id    =  $userAuth->id;
           }
           $consultant  = Consultant::find($consultant_id);
            if(!$consultant){
              return $this->returnError('001',trans('response_msg.user_id_err'));
            }
            if($request->has('picture')){
                $file_path=$this->saveImage('consultant',$request->picture);
                $data = $request->except(['picture']);
               if($request->has('password')){
                $data['password']   = bcrypt($request->password);
                }
                $data['picture']   =   $file_path;
                $consultant->update($data);
                return $this->returnData('consultant',$consultant,trans('response_msg.update')); 
            }
            if($request->has('password')){
                if( $request->password != null ){
                      // if (Hash::check($request->password , $user->password)) {
                        $password = $request->password;
                        $request->request->remove('password');
                        $request->request->add(['password' => bcrypt($password)]);
                        $consultant->update($request->all());
                        return $this->returnData('consultant',$consultant,trans('response_msg.change_password'));
                    // }
                    // else{
                    //     return $this->returnError('001', trans('response_msg.current_password'));
                    // }
                }
            }
            $consultant->update($request->all());
            return $this->returnData('consultant',$consultant,trans('response_msg.update'));       
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function destroy(Request $request)
        {
            try{
                $deleteValidate = $this->deleteValidate($request);
                if($deleteValidate){
                    return $deleteValidate;
                }
                $consultant = Consultant::find( $request->consultant_id);
                if(!$consultant){
                    return $this->returnError('001', trans('response_msg.user_id_err'));
                }
                $filePath1 = $consultant->picture;
                if($filePath1){
                 $url1=str_replace('http://aqar.local/','',$filePath1);
                 if(file_exists($url1)){
                     unlink($url1);
                 }
                }
                $consultant->delete();
                  return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
            }
            catch(\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());

            }
        }
        /// rest password Section 
       
       
      
       
       
       
    //     public function getUserTokens($user_id)
    //     {  
    //         $email=User::select('email')->where('id', $user_id)->first();
    //         $devices = Device::select('token')->where('email' ,$email['email'])  ->where('role',0)->get();  
    //          $tokens = array(); 
    //          foreach($devices as $device){
    //          $temp = [];
    //          $temp=$device->token;
    //         array_push($tokens, $temp);
    //         }
    //         return $tokens; 
    //         }
    //         public function RegisterDevice($email,$deviceToken)
    // {
    //   try{
    //      $deviceEmail= Device::select('token')->where('email',$email)->where('role',0)->get();
    //         if(count($deviceEmail)<1){
    //         $device=  Device::create([
    //             'email'  =>  $email,
    //             'token'  =>  $deviceToken,
    //             'role'   => 0,
    //             ]);
    //             return $this->returnData('device',$device,"تم  اضافة الجهاز بنجاح ");
    //         }
    //     return $this->returnSuccessMessage("تمت اضافة الجهاز مسبقا",'S000');
    //     }catch (\Exception $ex){
    //     return $this->returnError($ex->getCode(), $ex->getMessage());
    // }

    // }
    // public function getUserNotifications(Request $request)
    // {
    //     try{
    //         $rules = [
    //             'user_id'       =>"required|exists:users,id"
    //         ];
    //         $messages = [
    //             'user_id.required' => ' يرجى إدخال  رقم العميل',
    //             'user_id.exists' => 'عفوا هذا المستخدم غير موجود',
    //         ];
    //         $validator = Validator::make($request->all(), $rules, $messages);
           
    //         if ($validator->fails()) {
    //             $code = $this->returnCodeAccordingToInput($validator);
    //             return $this->returnValidationError($code, $validator);
    //         }
    //        // 
    //         $notifications = UserNotification::with(['salon' => function($query){
    //             $query->select('id','name');
    //         }])->select('title','message','sender_salon_id','created_at')
    //         ->where('receiver_user_id',$request->user_id)->orderby('id','desc')->get();
    //       $notifications[0]->created_at->diffForHumans(['options' => Carbon::NO_ZERO_DIFF]);
    //         if(count($notifications)<1){
    //             return $this->returnSuccessMessage("عفوا لا توجد اشعارات لديك",'S000');
    //         }
    //         for ($i=0; $i <count($notifications) ; $i++) { 
    //             $notifications[$i]->time= $notifications[$i]->created_at->diffForHumans(['options' => Carbon::NO_ZERO_DIFF]);;

    //         }
    //         return $this->returnData('notifications',$notifications,"تم استرجاع البيانات بنجاح ");
    //     }catch (\Exception $ex){
    //     return $this->returnError($ex->getCode(), $ex->getMessage());
    // }

    // } 
    // public function deleteUserNotifications(Request $request)
    // {
    //     try{
    //         $rules = [
    //             'id'            =>"required|exists:users_notifications,id",       
    //         ];
    //         $messages = [
    //              'id.required' => 'يرجى ادخال رقم الاشعار المراد حذفه ',
    //             'id.exists' => ' عفوا هذا الاشعار غير موجود',
    //         ];
    //         $validator = Validator::make($request->all(), $rules, $messages);
           
    //         if ($validator->fails()) {
    //             $code = $this->returnCodeAccordingToInput($validator);
    //             return $this->returnValidationError($code, $validator);
    //         }
    //          $notification = UserNotification::find($request->id);
    //      if(!$notification){
    //             return $this->returnSuccessMessage("عفوا لا توجد اشعارات لديك",'S000');
    //         }
    //         $notification->delete();
    //         return $this->returnSuccessMessage("تم الحذف بنجاح",'S000');
    //     }catch (\Exception $ex){
    //     return $this->returnError($ex->getCode(), $ex->getMessage());
    // }

    // } 

    // public function deleteAllUserNotifications(Request $request)
    // {
    //     try{
    //         $rules = [
    //             'user_id' =>"required|exists:users,id",    
    //             'id'            =>"required|exists:users_notifications,id",       
    //         ];
    //         $messages = [
    //             'user_id.required' =>"يرجى ادخال اسم المستخدم",    
    //             'user_id.exists' => ' عفوا هذا المستخدم غير موجود',
    //              'id.required' => 'يرجى ادخال رقم الاشعار المراد حذفه ',
    //             'id.exists' => ' عفوا هذا الاشعار غير موجود',
    //         ];
    //         $validator = Validator::make($request->all(), $rules, $messages);
    //         if ($validator->fails()) {
    //             $code = $this->returnCodeAccordingToInput($validator);
    //             return $this->returnValidationError($code, $validator);
    //         }
    //         // $notification = UserNotification::select('id')->where('')$request->id);
    //      if(!$notification){
    //             return $this->returnSuccessMessage("عفوا لا توجد اشعارات لديك",'S000');
    //         }
    //         $notification->delete();
    //         return $this->returnSuccessMessage("تم الحذف بنجاح",'S000');
    //     }catch (\Exception $ex){
    //     return $this->returnError($ex->getCode(), $ex->getMessage());
    // }

    // } 
    
    /*
    |--------------------------------------------------------------------------
    |  For send activation cod to user  email
    |--------------------------------------------------------------------------
    */
   
    // public function sendActivationCode($name,$email,$user_id)

    // {
    //     try{
    //         $userId =$user_id;
    //         $user = Otp::select('email')->where('receiver_id',$userId)->orderBy('id', 'desc')->first();
    //         if(!$user){
    //             try{
    //                 $name = $name;
    //                 $userEmail = User::select('email')->where('email',$email)->first();
    //                 $otp= $this->generateOtp(); 
    //                 $details = [
    //                     'greeting' => trans('response_msg.greeting') .' '. $name,
    //                     'body' => trans('response_msg.body').' : ' . $otp,
    //                     'thanks' => trans('response_msg.thanks'),
    //                 ];
    //             Notification::send($userEmail, new SendOtpRestPasswordNotification($details));
    //                     Otp::create([
    //                         'email'          => $email,
    //                         'code'            =>  $otp,
    //                         'receiver_id'    => $userId
    //                     ]);
    //             return $this->returnSuccessMessage(trans('response_msg.successOtp'),'S000');    
    //             }catch (\Exception $ex){
    //             return $this->returnError($ex->getCode(), $ex->getMessage());
    //             }
                
    //         }
    //         $name = $name;
    //         $emailDetails =User::select('email')->where('email',$email)->first();
    //         $old_otp =Otp::select('code')->where('receiver_id',$userId)->orderBy('id', 'desc')->first();
    //         $details = [
    //             'greeting' => trans('response_msg.greeting') .' '. $name,
    //             'body' => trans('response_msg.body').' : ' . $old_otp['code'],
    //             'thanks' => trans('response_msg.thanks'),
    
    //         ];
    //             Notification::send($emailDetails, new SendOtpRestPasswordNotification($details));
    //             return $this->returnSuccessMessage(trans('response_msg.successOtp'),'S000');                     

    //     }catch (\Exception $ex){
    //             return $this->returnError($ex->getCode(), $ex->getMessage());
    //         }
    // }

    /*
    |--------------------------------------------------------------------------
    |  For All Request Validation To This Controller 
    |--------------------------------------------------------------------------
    */
    public  function UpdateValidate($request)
    { 
        $rules = [
            'username'          => 'regex:/^\S*$/u|max:255|min:3',
            'name'              => 'string',
            'email'             => 'email',
            'mobile'            => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password'          => 'confirmed:min:8', 
            //'current_password'  => 'required_with:password',
            'consultant_id'     => 'exists:consultants,id',
            'company_id'        => 'exists:companies,id',
            'picture'           =>'mimes:png,gif,jpeg,jpg'
       ];
       $messages = [
           //'current_password'   => trans('validation'),
           'password'           => trans('validation.password'),
           'email'              => trans('validation.email'),
           'username'           => trans('validation.username'),
           'mobile'             => trans('validation.mobile'),    
           'consultant_id'      => trans('validation.consultant_id'),
           'company_id'         => trans('validation.company_id'),  
           'picture'            => trans('validation.picture'),   
  
       ];
       $validator = Validator::make($request->all(), $rules,$messages);
       if ($validator->fails()) {
           $code = $this->returnCodeAccordingToInput($validator);
           return $this->returnValidationError($code, $validator);
       }
    }
    public  function deleteValidate($request)
    { 
        $rules = [
            'consultant_id'     => 'required|exists:consultants,id',
       ];
       $messages = [
           'consultant_id'      => trans('validation.consultant_id'),
       ];
       $validator = Validator::make($request->all(), $rules,$messages);
       if ($validator->fails()) {
           $code = $this->returnCodeAccordingToInput($validator);
           return $this->returnValidationError($code, $validator);
       }
    }
}
