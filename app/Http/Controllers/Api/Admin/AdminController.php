<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Otp;
use App\Notifications\SendOtpRestPasswordNotification;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
class AdminController extends Controller
{
    use GeneralTrait;
    private $lang;   
     public function __construct(Request $request)
    {
        App::setlocale($request->lang);
        $this->lang= app()->getLocale();
    }
    public function notAdmin()
    {
       return $this->returnError('E001'," عفوا غير مسموح لك بهذه الصفحة " );
    }
   
  
    public function store(Request $request)
    { 
        try{
            $registerValidation =$this->registerValidate($request);
            if( $registerValidation){
                return $registerValidation;
            } 
                $admin =  Admin::create([
                        'name'      => $request->get('name'),
                        'email'     => $request->get('email'),
                        'password'  => bcrypt($request->get('password')),
                ]);
                $access_token = JWTAuth::fromUser($admin);
                $admin->access_token = $access_token;
                //$email=$request->get('email');
                // $this->RegisterDevice( $email,$request->deviceToken);   
                // $registrationIdsUsers =$this->getUserTokens($userId);
                // $UserTitle=" إشعار جديد";
                // $UserMessage="يا مرحبتين نور التطبيق";
                // $USER_API_KEY=env('USER_API_KEY');
                //  $this->send_notification($registrationIdsUsers,$UserTitle,$UserMessage,$USER_API_KEY);
            return $this->returnData('admin',$admin,trans('response_msg.register'));
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
           if($request->has('user_id')){
            $user_id    =  $request->user_id;
           }else{
            $admin = Auth::guard('admins')->user();
            if(!isset( $admin->id )){
                return $this->returnError('001',trans('response_msg.user_id_err'));
              }
            $user_id    =  $admin->id;
           }
           $user  = Admin::find($user_id);
            if(!$user){
              return $this->returnError('001',trans('response_msg.user_id_err'));
            }
            if($request->has('password')){
                if( $request->password != null ){
                      // if (Hash::check($request->password , $user->password)) {
                        $password = $request->password;
                        $request->request->remove('password');
                        $request->request->add(['password' => bcrypt($password)]);
                        $user->update($request->all());
                        return $this->returnData('user',$user,trans('response_msg.change_password'));
                    // }
                    // else{
                    //     return $this->returnError('001', trans('response_msg.current_password'));
                    // }
                }
            }
            $user->update($request->all());
            return $this->returnData('user',$user,trans('response_msg.update'));       
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function destroy(Request $request)
        {
            try{
                $admin =Admin::find( $request->admin_id);
                if(!$admin){
                    return $this->returnError('001', trans('response_msg.admin_id_err'));
                }
                $admin->delete();
                  return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
            }
            catch(\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());

            }
        }
      
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
    
 
    public  function updateValidate($request)
    {
        $rules = [
            'name'              =>"max:255|min:3",
            'email'             => 'email',
            'password'          => 'confirmed|min:8', 
            'current_password'  => 'required_with:update_password',
            'mobile'            => 'numeric|min:10',
       ];
       $messages = [
           'name'               =>  trans('validation.custom.update_name'),
           'email'              => trans('validation.email'),
           'mobile'             =>trans('validation.mobile'),
           'current_password'   =>  trans('validation.current_password'),
           'update_password'    =>  trans('validation.update_password'),
       ];
       $validator = Validator::make($request->all(), $rules,$messages);
       if ($validator->fails()) {
           $code = $this->returnCodeAccordingToInput($validator);
           return $this->returnValidationError($code, $validator);
       }
        
    }
   
}
