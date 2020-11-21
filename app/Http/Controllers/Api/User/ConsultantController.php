<?php

namespace App\Http\Controllers\Api\User;

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
    public function GetAllConsultant(Request $request)
    {
        try
        {
            $Consultants     = Consultant::with('rates')->get();
            if(count($Consultants) < 1){
                return $this->returnError('E002',trans('response_msg.users_err') );
            }
                return $this->returnData('Consultants',$Consultants,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function GetConsultant(Request $request)
    {
        try
        {
            $consultantValidate = $this->deleteValidate($request);
            if($consultantValidate){
                return $consultantValidate;
            }
            $Consultants     = Consultant::with('rates')->where('id',$request->consultant_id)->get();
            if(count($Consultants) < 1){
                return $this->returnError('E002',trans('response_msg.consultant_err') );
            }
                return $this->returnData('Consultants',$Consultants,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
   
    
    /*
    |--------------------------------------------------------------------------
    |  For All Request Validation To This Controller 
    |--------------------------------------------------------------------------
    */
    public  function UpdateValidate($request)
    { 
        $rules = [
            'consultant_id'     => 'exists:consultants,id',
            'company_id'        => 'exists:companies,id',
       ];
       $messages = [
           'consultant_id'      => trans('validation.consultant_id'),
           'company_id'         => trans('validation.company_id'),    
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
