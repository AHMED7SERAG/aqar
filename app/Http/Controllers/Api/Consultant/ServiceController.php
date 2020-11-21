<?php

namespace App\Http\Controllers\Api\Beaut;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use GeneralTrait;
    private $lang;   
     public function __construct(Request $request)
    {
        App::setlocale($request->lang);
        $this->lang= app()->getLocale();
        //$this->middleware(['is_admin']);      
    }
    public function GetAllServices()
    {
        try{
                $services = Service::select('id','name_'.$this->lang,'details_'.$this->lang,'icon',
                'estimated_time','price','bonus','location','category_id','beautician_id')->get();
                if(count($services) < 1){
                    return $this->returnError('E002',trans('response_msg.services_err') );
                }
                return $this->returnData('services',$services,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function store(Request $request)
    {
        try{
                $serviceValidate =$this->serviceValidate($request);
                if($serviceValidate){
                    return $serviceValidate;
                } 
                if($request->has('service_icon')){
                    $file_path = $this->saveImage('services',$request->service_icon);
                    $request->request->add(['icon'=> $file_path]);
                }
                $service  = Service::create($request->all());
                return $this->returnData('service',$service,trans('response_msg.save'));
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
                $service = Service::find($request->service_id);
                if(!$service){
                    return $this->returnError('E002',trans('response_msg.service_err') );
                }
                if($request->has('service_icon')){
                    $filePath1 = $service->icon;
                    if($filePath1){
                        $url1=str_replace('http://beaut.local/','',$filePath1);
                        if(file_exists($url1)){
                            unlink($url1);
                        }
                        $file_path=$this->saveImage('services',$request->service_icon);
                        $request->request->add(['icon'=>$file_path]);
                    }
                }
                $service->update($request->all());
                return $this->returnData('service',$service,trans('response_msg.update'));
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
                $service = Service::find($request->service_id);
                if(!$service){
                    return $this->returnError('E002',trans('response_msg.service_err') );
                }
                $filePath1 = $service->icon;
                if($filePath1){
                    $url1=str_replace('http://beaut.local/','',$filePath1);
                    if(file_exists($url1)){
                        unlink($url1);
                    }         
                }
                $service->delete();
                return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public  function serviceValidate($request)
    {
        $rules = [
             'name_ar'             =>'required', 
             'name_en'             =>'required', 
             'details_ar'          =>'required', 
             'details_en'          =>'required',  
             'estimated_time'      =>'required|numeric',
             'price'               =>'required|numeric',
             'bonus'               =>'numeric',
             'location'            =>'required|numeric|in:0,1',
             'service_icon'        =>'required|mimes:gif,jpeg,jpg,png' ,
             'beautician_id'       =>'required|exists:beauticians,id',                       
             'category_id'         =>'required|exists:categories,id',
        ];
        $messages = [
            'name_ar.'                => trans('validation.name_ar'),
            'name_en'                 => trans('validation.name_ar'),
            'details_ar'              => trans('validation.details_ar'), 
            'details_en'              => trans('validation.details_en'),
            'estimated_time'          => trans('validation.estimated_time'),
            'price'                   => trans('validation.price'),
            'bonus'                   => trans('validation.bonus'),
            'location'                => trans('validation.location'),
            'service_icon'            => trans('validation.service_icon'),
            'beautician_id'           => trans('validation.beautician_id'),
            'category_id'             => trans('validation.category_id'),
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
             'service_id'              =>'required|exists:services,id',    
             'service_icon'            =>'mimes:gif,jpeg,jpg,png'   ,
             'category_id'             =>'exists:categories,id',                           
             'beautician_id'           =>'exists:beauticians,id',                             
        ];
        $messages = [
             'service_id'              => trans('validation.service_id'),
             'service_icon'            => trans('validation.service_icon'),
             'category_id'             => trans('validation.category_id'),
             'beautician_id'           => trans('validation.beautician_id'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
