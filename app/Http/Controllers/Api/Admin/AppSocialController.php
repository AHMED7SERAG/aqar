<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSocial;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class AppSocialController extends Controller
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
    public function getAppSocial()
    {
        try{
                $social = AppSocial::get();
                if(count($social) < 1){
                    return $this->returnError('E002',trans('response_msg.social_err') );
                }
                return $this->returnData('social',$social,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function store(Request $request)
    {
        try{
                $socialValidate =$this->socialValidate($request);
                if($socialValidate){
                    return $socialValidate;
                } 
                if($request->has('logo')){
                    $file_path = $this->saveImage('setting',$request->logo);
                    $data = $request->only([ 'name','link']);
                    $data['logo'] = $file_path;
                }
                $setting  = AppSocial::create($data);
                return $this->returnData('setting',$setting,trans('response_msg.socialSave'));
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
                $social = AppSocial::find($request->social_id);
                if(!$social){
                    return $this->returnError('E002',trans('response_msg.social_id_err') );
                }
                if($request->has('logo')){
                    $filePath1 = $social->logo;
                    if($filePath1){
                        $url1=str_replace('http://aqar.local/','',$filePath1);
                        if(file_exists($url1)){
                            unlink($url1);
                        }
                        $file_path=$this->saveImage('setting',$request->logo);
                        $data = $request->only([ 'name','link']);
                        $data['logo'] = $file_path;
                        $social->update($data);
                        return $this->returnData('social',$social,trans('response_msg.update'));
                    }
                }
                $social->update($request->only(['name','link']));
                return $this->returnData('social',$social,trans('response_msg.update'));
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
                $social = AppSocial::find($request->social_id);
                if(!$social){
                    return $this->returnError('E002',trans('response_msg.social_id_err') );
                }
                $filePath1 = $social->logo;
                if($filePath1){
                    $url1=str_replace('http://aqar.local/','',$filePath1);
                    if(file_exists($url1)){
                        unlink($url1);
                    }         
                }
                $social->delete();
                return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public  function socialValidate($request)
    {
        $rules = [
             'name'            =>'required|string', 
             'link'            =>'required|url', 
             'logo'            =>'required|mimes:gif,jpeg,jpg,png'           
        ];
        $messages = [
            'name'             => trans('validation.name'),
            'link'             => trans('validation.link'),
            'logo'             => trans('validation.logo'),
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
             'social_id'            =>'required|exists:app_social,id',
             'name'                 =>'string', 
             'link'                 =>'url', 
             'logo'                 =>'mimes:gif,jpeg,jpg,png'          
            ];
        $messages = [
            'social_id'             => trans('validation.social_id'),
            'name'                  => trans('validation.name'),
            'link'                  => trans('validation.link'),
            'logo'                  => trans('validation.logo'),       
         ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
