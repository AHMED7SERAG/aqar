<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
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
                $contacts = ContactUs::get();
                if(count($contacts) < 1){
                    return $this->returnError('E002',trans('response_msg.contacts_err') );
                }
                return $this->returnData('contacts',$contacts,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function store(Request $request)
    {
        try{
                $contactValidate =$this->contactValidate($request);
                if($contactValidate){
                    return $contactValidate;
                } 
                $contact  = ContactUs::create($request->all());
                return $this->returnData('contact',$contact,trans('response_msg.contacts_Save'));
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
                $contact = ContactUs::find($request->contact_id);
                if(!$contact){
                    return $this->returnError('E002',trans('response_msg.contacts_id_err') );
                }
                $contact->update($request->all());
                return $this->returnData('contact',$contact,trans('response_msg.update'));
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
                $contact = ContactUs::find($request->contact_id);
                if(!$contact){
                    return $this->returnError('E002',trans('response_msg.contacts_id_err') );
                }
                $contact->delete();
                return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public  function contactValidate($request)
    {
        $rules = [
             'name'            =>'required|string', 
             'mobile'          =>'required|string', 
             'details'         =>'required|string|max:255'           
        ];
        $messages = [
            'name'             => trans('validation.name'),
            'mobile'           => trans('validation.mobile'),
            'details'          => trans('validation.details'),
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
             'contact_id'              =>'exists:contact_us,id',
             'name'                    =>'string', 
             'mobile'                  =>'string', 
             'details'                 =>'string|max:255'            
            ];
        $messages = [
            'contact_id'               => trans('validation.contact_id'),
            'name'                     => trans('validation.name'),
            'mobile'                   => trans('validation.mobile'),
            'details'                  => trans('validation.details'),
              ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
