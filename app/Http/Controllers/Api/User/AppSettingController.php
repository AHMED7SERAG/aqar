<?php

namespace App\Http\Controllers\Api\User;

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
}
