<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Commitment;
use App\Models\FinanceApplication;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FinanceApplicationController extends Controller
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
    public function getUserFinanceApplication(Request $request)
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
                $finance_application = FinanceApplication::with(['user','commitment'])->where('user_id',$user_id)->get();
                if(count($finance_application) < 1){
                    return $this->returnError('E002',trans('response_msg.financeApplication_err') );
                }
                return $this->returnData('finance_application',$finance_application,trans('response_msg.data'));
            }catch (\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
    }
    public function store(Request $request)
    {
        try{
                $financeValidate =$this->financeValidate($request);
                if($financeValidate){
                    return $financeValidate;
                }
                if($request->has('user_id')){
                    $request->request->add(['user_id' => $request->user_id]);
                }
                else{
                $userAuth   = Auth::guard('users')->user();
                if(!isset( $userAuth->id )){
                    return $this->returnError('001',trans('response_msg.user_id_err'));
                    }
                    $request->request->add(['user_id' =>  $userAuth->id]);
                }
                DB::beginTransaction();
                $finance_application  = FinanceApplication::create($request->only([
                    'full_name','bank_salary','salary','total_salary','deduction','employer','occupation','service_length',	'remain_service_life','user_id',
                ]));
                $request->request->add(['finance_application_id' =>  $finance_application->id]);
                $hand_commitments   = $this->stringToArry($request->hand_commitment);
                $monthly_amount     = $this->stringToArry($request->monthly_amount);
                $remaining_months   = $this->stringToArry($request->remaining_months);
                $i = 0;
                if(count($hand_commitments) != count($monthly_amount)){
                    return $this->returnError('E000',trans('response_msg.finance_err'));
                }
                foreach ($hand_commitments as $hand_commitment ) {
                    Commitment::create([ 
                        'hand_commitment'            => $hand_commitment,
                        'monthly_amount'             => $monthly_amount[$i],
                        'remaining_months'           => $remaining_months[$i],
                        'finance_application_id'     => $finance_application->id
                    ]);
                    $i++;
                }
                DB::commit();
                $id = $finance_application->id;
                $finance_application   = FinanceApplication::with(['commitment'])->where('id' ,$id)->get();
                return $this->returnData('finance_application',$finance_application,trans('response_msg.financeSave'));
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
                $finance_application = FinanceApplication::find($request->finance_id);
                if(!$finance_application){
                    return $this->returnError('E002',trans('response_msg.finance_err') );
                }
                $finance_application->update($request->all());
                if($request->has('hand_commitment') ||$request->has('remaining_months') ||$request->has('monthly_amount')){
                    $commitment = Commitment::where('finance_application_id',$request->finance_id)->first();
                    $commitment->update($request->all());
                }
                $id = $finance_application->id;
                $finance_application   = FinanceApplication::with(['commitment'])->where('id' ,$id)->get();
                return $this->returnData('finance_application',$finance_application,trans('response_msg.update'));
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
                $Finance_application = FinanceApplication::find($request->finance_id);
                if(!$Finance_application){
                    return $this->returnError('E002',trans('response_msg.finance_err') );
                }
                $commitment = Commitment::where('finance_application_id',$request->finance_id)->get();
                if(count($commitment) > 0){
                    foreach($commitment as $commit){
                    $commit->delete();
                   }
                }
                $Finance_application->delete();
                return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
             }catch (\Exception $ex){
                  return $this->returnError($ex->getCode(), $ex->getMessage());
             }
    }
    public function stringToArry($request)
        {

            $str=trim($request,"[]");
            $pieces = explode(",", $str);
            $arr=[];
            foreach ($pieces as $piece) {
                array_push( $arr,$piece);
            }
            return $arr;
        }
    public  function financeValidate($request)
    {
        $rules = [
             'full_name'             =>'required|string' , 
             'bank_salary'           =>'required|numeric', 
             'salary'                =>'required|numeric',
             'total_salary'          =>'required|numeric',
             'deduction'             =>'required|numeric', 
             'employer'              =>'required|string' , 
             'occupation'            =>'required|string' ,
             'service_length'        =>'required|string' , 
             'remain_service_life'   =>'required|string' ,
             'hand_commitment'       =>'required|string' ,
             'monthly_amount'        =>'required|string', 
             'remaining_months'      =>'required|string', 
             'user_id'               =>'exists:users,id', 
        ];
        $messages = [
            'full_name.'             => trans('validation.full_name'),
            'bank_salary'            => trans('validation.bank_salary'),
            'salary'                 => trans('validation.salary'),
            'total_salary'           => trans('validation.total_salary'),
            'deduction'              => trans('validation.deduction'),
            'employer'               => trans('validation.employer'),
            'occupation'             => trans('validation.occupation'),
            'service_length'         => trans('validation.service_length'),
            'remain_service_life'    => trans('validation.remain_service_life'),
            'hand_commitment'        => trans('validation.hand_commitment'),
            'monthly_amount.'        => trans('validation.monthly_amount'),
            'remaining_months'       => trans('validation.remaining_months'),
            'user_id'                => trans('validation.user_id'),
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
             'finance_id'           =>'required|exists:finance_application,id',
             'full_name'             =>'string' , 
             'bank_salary'           =>'numeric', 
             'salary'                =>'numeric',
             'total_salary'          =>'numeric',
             'deduction'             =>'numeric', 
             'employer'              =>'string' , 
             'occupation'            =>'string' ,
             'service_length'        =>'string' , 
             'remain_service_life'   =>'string' ,
             'hand_commitment'       =>'string' ,
             'monthly_amount'        =>'numeric', 
             'remaining_months'      =>'numeric', 
             'user_id'               =>'exists:users,id',      
        ];
        $messages = [
            'finance_id'             => trans('validation.finance_id'),
            'full_name.'             => trans('validation.full_name'),
            'bank_salary'            => trans('validation.bank_salary'),
            'salary'                 => trans('validation.salary'),
            'total_salary'           => trans('validation.total_salary'),
            'deduction'              => trans('validation.deduction'),
            'employer'               => trans('validation.employer'),
            'occupation'             => trans('validation.occupation'),
            'service_length'         => trans('validation.service_length'),
            'remain_service_life'    => trans('validation.remain_service_life'),
            'hand_commitment'        => trans('validation.hand_commitment'),
            'monthly_amount.'        => trans('validation.monthly_amount'),
            'remaining_months'       => trans('validation.remaining_months'),
            'user_id'                => trans('validation.user_id'),
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
             'finance_id'   =>'required|exists:finance_application,id',
        ];
        $messages = [
            'finance_id'    => trans('validation.finance_id'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
