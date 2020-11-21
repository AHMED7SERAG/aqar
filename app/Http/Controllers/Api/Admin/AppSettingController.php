<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class AppSettingController extends Controller
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
    public function getAppSetting()
    {
        try{
                $setting = AppSetting::get();
                if(count($setting) < 1){
                    return $this->returnError('E002',trans('response_msg.setting_err') );
                }
                return $this->returnData('setting',$setting,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function store(Request $request)
    {
        try{
                $settingValidate =$this->settingValidate($request);
                if($settingValidate){
                    return $settingValidate;
                } 
                if($request->has('logo')){
                    $file_path = $this->saveImage('setting',$request->logo);
                    $data = $request->only([ 'app_name','app_version']);
                    $data['logo'] = $file_path;
                }
                $setting  = AppSetting::create($data);
                return $this->returnData('setting',$setting,trans('response_msg.settingSave'));
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
                $setting = AppSetting::find($request->setting_id);
                if(!$setting){
                    return $this->returnError('E002',trans('response_msg.setting_id_err') );
                }
                if($request->has('logo')){
                    $filePath1 = $setting->logo;
                    if($filePath1){
                        $url1=str_replace('http://aqar.local/','',$filePath1);
                        if(file_exists($url1)){
                            unlink($url1);
                        }
                        $file_path=$this->saveImage('setting',$request->logo);
                        $data = $request->only([ 'app_name','app_version']);
                        $data['logo'] = $file_path;
                        $setting->update($data);
                        return $this->returnData('setting',$setting,trans('response_msg.update'));
                    }
                }
                $setting->update($request->only([ 'app_name','app_version']));
                return $this->returnData('setting',$setting,trans('response_msg.update'));
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public function destroy(Request $request)
    {
        try{
                $updateValidation =$this->updateValidation($request);
                if($updateValidation){
                    return $updateValidation;
                } 
                $setting = AppSetting::find($request->setting_id);
                if(!$setting){
                    return $this->returnError('E002',trans('response_msg.setting_id_err') );
                }
                $filePath1 = $setting->logo;
                if($filePath1){
                    $url1=str_replace('http://aqar.local/','',$filePath1);
                    if(file_exists($url1)){
                        unlink($url1);
                    }         
                }
                $setting->delete();
                return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public  function settingValidate($request)
    {
        $rules = [
             'app_name'            =>'required|string', 
             'app_version'         =>'required|string', 
             'logo'                =>'required|mimes:gif,jpeg,jpg,png'           
        ];
        $messages = [
            'app_name.'            => trans('validation.app_name'),
            'app_version'          => trans('validation.app_version'),
            'logo'                 => trans('validation.logo'),
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
             'setting_id'              =>'required|exists:app_settings,id',
             'app_name'                =>'string', 
             'app_version'             =>'string', 
             'logo'                    =>'mimes:gif,jpeg,jpg,png'          ];
        $messages = [
            'setting_id'               => trans('validation.setting_id'),
            'app_name.'                => trans('validation.app_name'),
            'app_version'              => trans('validation.app_version'),
            'logo'                     => trans('validation.logo'),        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
