<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\MaintenancePicture;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MaintenanceController extends Controller
{
    use GeneralTrait;
    private $lang;   
     public function __construct(Request $request)
    {
        if(isset($request->lang)){
            App::setlocale($request->lang);
            $this->lang= app()->getLocale();  
        }
        else
            $this->lang= app()->getLocale();  
    }
    public function getUserMaintenance(Request $request)
    {
        try{
            if($request->has('user_id')){
                $user_id = $request->user_id;
               }else{
                $userAuth   = Auth::guard('users')->user();
                if(!isset( $userAuth->id )){
                    return $this->returnError('001',trans('response_msg.user_id_err'));
                  }
                 $user_id   = $userAuth->id;
               }
                $maintenance = Maintenance::with('user')->where('user_id',$user_id)->get();
                if(count($maintenance) < 1){
                    return $this->returnError('E002',trans('response_msg.maintenances_err') );
                }
                return $this->returnData('maintenance',$maintenance,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function store(Request $request)
    {
        try{
                $maintenanceValidate =$this->maintenanceValidate($request);
                if($maintenanceValidate){
                    return $maintenanceValidate;
                }
                if($request->has('user_id')){
                    $request->request->add(['user_id' => $request->user_id]);
                   }else{
                    $userAuth   = Auth::guard('users')->user();
                    if(!isset( $userAuth->id )){
                        return $this->returnError('001',trans('response_msg.user_id_err'));
                      }
                      $request->request->add(['user_id' =>  $userAuth->id]);
                   }
                   $data = $request->only('user_id','date','type','time');
                   $maintenance  = Maintenance::create($data);
                if($request->has('pictures')){
                    $pictures = $request->pictures;
                    foreach ($pictures as $picture) {
                        $file_path = $this->saveImage('maintenance',$picture);
                        MaintenancePicture::create([
                            'maintenance_id' => $maintenance->id, 
                            'picture'        => $file_path,
                        ]);
                    }
                 }
                 $maintenance  = Maintenance::with('pictures')->find($maintenance->id);
                return $this->returnData('maintenance',$maintenance,trans('response_msg.maintenanceSave'));
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public function update(Request $request)
    {
        try{
                $updateValidation =$this->updateValidation($request);
                if($updateValidation){
                    return $updateValidation;
                } 
                $maintenance = Maintenance::find($request->maintenance_id);
                if(!$maintenance){
                    return $this->returnError('E002',trans('response_msg.maintenance_err') );
                }
                if($request->has('pictures')){
                    foreach ($request->pictures as $picture) {
                        $oldMethod = MaintenancePicture::where('maintenance_id', $maintenance->id)->get();
                        foreach ($oldMethod as $key) {
                            $filePath1 = $key->picture;
                            if($filePath1){
                                $url1=str_replace('http://Aqar.local/','',$filePath1);
                                if(file_exists($url1)){
                                    unlink($url1);
                                }
                            }
                            $key->delete();
                        } 
                        $file_path=$this->saveImage('maintenance',$picture);
                        MaintenancePicture::create([
                            'maintenance_id' => $maintenance->id, 
                            'picture'        => $file_path,
                        ]);
                    }
                }
                $maintenance->update($request->all());
                return $this->returnData('maintenance',$maintenance,trans('response_msg.update'));
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public function destroy(Request $request)
    {
        try{
                $deleteValidation =$this->deleteValidation($request);
                if($deleteValidation){
                    return $deleteValidation;
                } 
                $maintenance = Maintenance::find($request->maintenance_id);
                if(!$maintenance){
                    return $this->returnError('E002',trans('response_msg.maintenance_err') );
                }
                $oldPicture= MaintenancePicture::where('maintenance_id', $maintenance->id)->get();
                foreach ($oldPicture as $key) {
                    $filePath1 = $key->picture;
                    if($filePath1){
                        $url1=str_replace('http://aqar.local/','',$filePath1);
                        if(file_exists($url1)){
                            unlink($url1);
                        }
                    }
                    $key->delete();
                } 
                $maintenance->delete();
                return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public  function maintenanceValidate($request)
    {
        $rules = [
             'date'             =>'required|date_format:"Y-m-d"', 
             'time'             =>'required|date_format:"H:i"', 
             'type'             =>'required|string',
             'pictures'         =>'required|array',
             'pictures.*'       =>'mimes:gif,jpeg,jpg,png', 
             'user_id'          =>'exists:users,id', 
        ];
        $messages = [
            'date.'             => trans('validation.date'),
            'time'              => trans('validation.time'),
            'type'              => trans('validation.type'),
            'pictures'          => trans('validation.pictures'),
            'user_id'           => trans('validation.user_id'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function updateValidation($request)
    {
        $rules = [
             'maintenance_id'   =>'required|exists:maintenance,id',
             'date'             =>'date_format:"Y-m-d"', 
             'time'             =>'date_format:"H:i"', 
             'type'             =>'string',
             'picture'          =>'mimes:gif,jpeg,jpg,png', 
             'user_id'          =>'exists:users,id',                  
        ];
        $messages = [
            'maintenance_id'    => trans('validation.maintenance_id'),
            'date'              => trans('validation.date'),
            'time'              => trans('validation.time'),
            'type'              => trans('validation.type'),
            'picture'           => trans('validation.picture'),
            'user_id'           => trans('validation.user_id'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function deleteValidation($request)
    {
        $rules = [
             'maintenance_id'   =>'required|exists:maintenance,id',
        ];
        $messages = [
            'maintenance_id'    => trans('validation.maintenance_id'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
