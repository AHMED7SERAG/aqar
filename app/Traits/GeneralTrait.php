<?php

namespace App\Traits;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

trait GeneralTrait
{
    public function getAuthenticatedUser()
    {
    try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return $this->returnError('001',trans('response_msg.user_id_err'));
            }
            } catch (Exception $e) {
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return $this->returnError('T002', trans('response_msg.token_invalid'));
                }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return $this->returnError('T001',trans('response_msg.token_expired'));
                }else{
                    return $this->returnError('T003', trans('response_msg.token_notFound'));
                }
            }
            return $user;
    }
    public function generateOtp(){
        $fourDigitRandom = rand(1000,9999); 
        return $fourDigitRandom; 
    }
    public function saveImage($folder ,$image)
    {
        $image->store('/',$folder);
        $file_name=$image->hashName();
        $path='images/'.$folder.'/'.$file_name;
        return $path;
    }
    public function stringToArry($arr)
    {

        $str=trim($arr,"[]");
        $pieces = explode(",", $str);
        $arr=[];
        foreach ($pieces as $piece) {
            $int = (int)$piece;
            array_push( $arr,$int);
        }
        return $arr;
    }
    public function stringDate($arr)
    {

        $str = $arr;
        $pieces = explode("-", $str);
        $arr=[];
        foreach ($pieces as $piece) {
            $int = (int)$piece;
            array_push( $arr,$int);
        }
        return  $pieces = implode("-", $arr);
    }
    public function getCurrentLang()
    {
        return app()->getLocale();
    }
    function default_lang()
    {
        return config('app.locale');
    }
    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }


    public function returnSuccessMessage($msg = "", $errNum = "S000" )
    {
        return [
            'status' => true,
            'errNum' => $errNum,
            'msg' => $msg,
            
        ];
    }

    public function returnData($key, $value, $msg = "")
    {
        return response()->json([
            'status' => true,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ]);
    }


    //////////////////
    public function returnValidationError($code = "E001", $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0001';

        else if ($input == "password")
            return 'E002';

        else if ($input == "email")
            return 'E003';

        else if ($input == "mobile")
            return 'E004';

        else if ($input == "address")
            return 'E005';

        else if ($input == "longitude")
            return 'E006';

        else if ($input == "latitude")
            return 'E007';

        else if ($input == "holder_name")
            return 'E008';

        else if ($input == "number")
            return 'E009';

        else if ($input == "exp_date")
            return 'E010';

        else if ($input == "cvv")  
            return 'E011';
        else if ($input == "code")
            return 'E012';

        else if ($input == "location_id")
            return 'E013';

        else if ($input == "update_name")
            return 'E014';

        else if ($input == "update_email")
            return 'E015';

        else if ($input == "current_password" || $input == "update_password")
            return 'E016';

        else if ($input == "card_id")
            return 'E017';

        else if ($input == "name_en")
            return 'E018';

        else if ($input == "name_ar")
            return 'E019';

        else if ($input == "payment_id") 
            return 'E020';

        else if ($input == "name_ar")
            return 'E021';

        else if ($input == "name_en")
            return 'E022';

        else if ($input == "icon")
            return 'E023';

        else if ($input == "category_id")
            return 'E024';

        else if ($input == "city_id")
            return 'E025';

        else if ($input == "coupon_id")
            return 'E026';

        else if ($input == "code")
            return 'E027';

        else if ($input == "type")
            return 'E028';

        else if ($input == "value")
            return 'E029';

        else if ($input == "details_ar")
            return 'E030';

        else if ($input == "details_en")
            return 'E031';

        else if ($input == "service_icon")
            return 'E032';

        else if ($input == "payment_method_id")
            return 'E033';

        else if ($input == "photos")
            return 'E034';

        else if ($input == "owner_name")
            return 'E035';

        else if ($input == "beaut_name")
            return 'E036';

        else if ($input == "photo")
            return 'E037';

        else if ($input == "logo")
            return 'E038';

        else if ($input == "rate_id")
            return 'E039';

        else if ($input == "value")
            return 'E040';

        else if ($input == "comment")
            return 'E041';

        else if ($input == "beautician_id")
            return 'E042';

        else if ($input == "photo_id")/////////////////////////////////////////////////
            return 'E043';

        else if ($input == "nickname_id")
            return 'E044';

        else if ($input == "reservation_id")
            return 'E045';

        else if ($input == "attachments")
            return 'E046';

        else if ($input == "summary")
            return 'E047';

        else if ($input == "user_id")
            return 'E048';

        else if ($input == "mobile_id")
            return 'E049';

        else if ($input == "paid")
            return 'E050';

        else if ($input == "use_insurance")
            return 'E051';

        else if ($input == "doctor_rate")
            return 'E052';

        else if ($input == "provider_rate")
            return 'E053';

        else if ($input == "message_id")
            return 'E054';

        else if ($input == "hide")
            return 'E055';

        else if ($input == "checkoutId")
            return 'E056';

        else
            return "";
    }


}