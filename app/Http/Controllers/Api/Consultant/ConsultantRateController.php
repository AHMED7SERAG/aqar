<?php

namespace App\Http\Controllers\Api\Consultant;

use App\Http\Controllers\Controller;
use App\Models\Consultant;
use App\Models\ConsultantRate;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConsultantRateController extends Controller
{
    use GeneralTrait;
    private $lang;   
     public function __construct(Request $request)
    {
        App::setlocale($request->lang);
        $this->lang= app()->getLocale();
       // $this->middleware(['is_admin']);      
    }
    public function GetAllConsultantRate()
    {
        try{
                $ConsultantRate = ConsultantRate::with(['user'  => function ($query){
                    $query->select('id' ,'name');
                },'consultant'  => function ($query){
                    $query->select('id' ,'name');
                }])->get();
                if(count($ConsultantRate) < 1){
                    return $this->returnError('E002',trans('response_msg.ConsultantRate_err') );
                }
                return $this->returnData('consultant_rate',$ConsultantRate,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function GetAllBeauticianRateForUser()
    {
        try{
                $user = $this->getAuthenticatedUser();
                $BeauticianRate = BeauticianRate::where('user_id',$user->id)->get();
                if(count($BeauticianRate) < 1){
                    return $this->returnError('E002',trans('response_msg.ConsultantRate_err') );
                }
                return $this->returnData('BeauticianRate',$BeauticianRate,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function GetBeauticianRates(Request $request)
    {
        try{
            $BeauticianRateValidate = $this->BeauticianRateValidate($request);
                if($BeauticianRateValidate){
                    return $BeauticianRateValidate;
                }
                if($request->has('beautician_id')){
                    $beautician_id  = $request->beautician_id;
                }else{
                        $beautician     = Auth::guard('beautician')->user();
                        if($beautician ){
                            $beautician_id  = $beautician->id;
                        }else{
                            return $this->returnError('001',trans('response_msg.beautician_id_err'));
                        }
                }
                $beautician      = Beautician::find($beautician_id);
                $BeauticianRate  = BeauticianRate::with('user')->where('beautician_id',$beautician_id)->get();
                if(count($BeauticianRate) < 1){
                    return $this->returnError('E002',trans('response_msg.BeauticianRate_err') );
                }
                return $this->returnData('BeauticianRate',$BeauticianRate,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function store(Request $request)
    {
        try{
                $RateValidate = $this->RateValidate($request);
                if($RateValidate){
                    return $RateValidate;
                }
                if($request->has('user_id')){
                    $request->request->add(['user_id' => $request->user_id]);
                }else{
                        $user   = Auth::guard('users')->user();
                        if($user ){
                            $request->request->add(['user_id' => $user->id]);
                        }else{
                            return $this->returnError('001',trans('response_msg.beautician_id_err'));
                        }
                }
                //DB::beginTransaction();
                $ConsultantRate    =  ConsultantRate::create($request->all());
                $consultant_id     = $request->consultant_id;
              return   $this->ConsultantTotalRate($consultant_id);
                //DB::commit();
                return $this->returnData('ConsultantRate',$ConsultantRate,trans('response_msg.save'));
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    // public function update(Request $request)
    // {
    //     try{
    //             $updateValidation =$this->updateValidation($request);
    //             if($updateValidation){
    //                 return $updateValidation;
    //             } 
    //             $BeauticianRate = BeauticianRate::find($request->rate_id);
    //             DB::beginTransaction();
    //             $BeauticianRate->update($request->all());
    //             $beautician_id  = $BeauticianRate->beautician_id;
    //             $this->BeauticianTotalRate($beautician_id);
    //             if(!$BeauticianRate){
    //                 return $this->returnError('E002',trans('response_msg.BeauticianRate_err') );
    //             }
    //             DB::commit();
    //             return $this->returnData('BeauticianRate',$BeauticianRate,trans('response_msg.update'));
    //          }catch (\Exception $ex){
    //               return $this->returnError($ex->getCode(), $ex->getMessage());
    //          }
    // }
    // public function destroy(Request $request)
    // {
    //     try{
    //             $deleteValidation =$this->deleteValidation($request);
    //             if($deleteValidation){
    //                 return $deleteValidation;
    //             } 
    //             $BeauticianRate = BeauticianRate::find($request->rate_id);
    //             if(!$BeauticianRate){
    //                 return $this->returnError('E002',trans('response_msg.rate_id_err') );
    //             }
    //             DB::beginTransaction();
    //             $BeauticianRate->delete();
    //             $beautician_id  = $BeauticianRate->beautician_id;
    //             $this->BeauticianTotalRate($beautician_id);
    //             if(!$BeauticianRate){
    //                 return $this->returnError('E002',trans('response_msg.BeauticianRate_err') );
    //             }
    //             DB::commit();
    //             return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
    //          }catch (\Exception $ex){
    //               return $this->returnError($ex->getCode(), $ex->getMessage());
    //          }
    // }
     /*
    |--------------------------------------------------------------------------
    |  For  Calculate Beautician Total Rate 
    |--------------------------------------------------------------------------
    */
    public  function ConsultantTotalRate($consultant_id)
    {
        $consultant_id         = $consultant_id;
        $ConsultantTotalRate   = Consultant::find($consultant_id);
        $ConsultantRate        = ConsultantRate::where('consultant_id',$consultant_id)->get();
        $number_of_rate        = count($ConsultantRate);
        $execution_speed                = [];
        $execution_quality              = [];
        $explanation_clarification      = [];
        $permanent_presence             = [];
        $effective_communication        = [];
        foreach ($ConsultantRate as $rate ) {
             $execution_speed[]              = $rate['execution_speed'];
             $execution_quality[]            = $rate['execution_quality'];
             $explanation_clarification[]    = $rate['explanation_clarification'];
             $permanent_presence[]           = $rate['permanent_presence'];
             $effective_communication[]      = $rate['effective_communication'];
        }
        if($number_of_rate == 0){
           $number_of_rate = 1;
        }
        $execution_speed                =   array_sum ($execution_speed )/$number_of_rate;
        $execution_quality              =   array_sum ($execution_quality )/$number_of_rate;
        $explanation_clarification      =   array_sum ($explanation_clarification )/$number_of_rate;
        $permanent_presence             =   array_sum ($permanent_presence )/$number_of_rate;
        $effective_communication        =   array_sum ($effective_communication )/$number_of_rate;
        $ConsultantTotalRate->update([
            'execution_speed'             =>   $execution_speed,
            'execution_quality'           =>    $execution_quality,
            'explanation_clarification'   =>    $explanation_clarification,
            'permanent_presence'          =>    $permanent_presence,
            'effective_communication'     =>   $effective_communication,
        ]);
        return $ConsultantTotalRate;
      
    }
    /*
    |--------------------------------------------------------------------------
    |  For  validation  Functions
    |--------------------------------------------------------------------------
    */

    public  function RateValidate($request)
    {
        $rules = [
             'execution_speed'               =>'required|numeric|between:1,5', 
             'execution_quality'             =>'required|numeric|between:1,5', 
             'explanation_clarification'     =>'required|numeric|between:1,5', 
             'permanent_presence'            =>'required|numeric|between:1,5', 
             'effective_communication'       =>'required|numeric|between:1,5', 
             'comment'                       =>'required|string|max:200|min:2', 
             'consultant_id'                 =>'required|exists:consultants,id',
        ];
        $messages = [
            'execution_speed'                => trans('validation.execution_speed'),
            'execution_quality'              => trans('validation.execution_quality'),
            'explanation_clarification'      => trans('validation.explanation_clarification'),
            'permanent_presence'             => trans('validation.permanent_presence'),
            'effective_communication'        => trans('validation.effective_communication'),
            'comment'                        => trans('validation.comment'),
            'consultant_id'                  => trans('validation.consultant_id'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function BeauticianRateValidate($request)
    {
        $rules = [
             'beautician_id'       =>'exists:beauticians,id',
        ];
        $messages = [
            'beautician_id'         => trans('validation.beautician_id'),
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
             'rate_id'                  =>'required|exists:beautician_rate,id',
             'value'                    =>'numeric|between:1,5', 
             'comment'                  =>'string|max:200|min:2', 
             'beautician_id'            =>'exists:beauticians,id',
             //'order_num'                =>'exists:orders,order_num',
             'user_id'                  =>'exists:users,id'              
        ];
        $messages = [
            'rate_id'                   => trans('validation.rate_id'),
            'value.'                    => trans('validation.value'),
            'comment'                   => trans('validation.comment'),
            'beautician_id'             => trans('validation.beautician_id'),
            'user_id'                   => trans('validation.user_id'),
            'order_num'                 =>trans('validation.order_num'),

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
             'rate_id'                  =>'required|exists:beautician_rate,id',
        ];
        $messages = [
            'rate_id'                   => trans('validation.rate_id'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
