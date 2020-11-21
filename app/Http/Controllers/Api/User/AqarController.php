<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Flat;
use App\Models\Gallery;
use App\Models\Land;
use App\Models\Villa;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function Ramsey\Uuid\v1;

class AqarController extends Controller
{
    use GeneralTrait;
    public function getHomePage()
    {
        try{
            $land   = Land::with(['gallery' =>  function ($query){
                $query->where('aqar_type' , 0);
            }])->get();
             
            $flat   = Flat::with(['gallery' =>  function ($query){
                $query->where('aqar_type' , 1);
            }])->get();

            $villa   = Villa::with(['gallery' =>  function ($query){
                $query->where('aqar_type' , 2);
            }])->get();

            $building   = Building::with(['gallery' =>  function ($query){
                $query->where('aqar_type' , 3);
            }])->get();
            $lands =[ 
                'aqar_type'       => 0,
                'data_lands'      => $land
            ];
            $flats =[ 
                'aqar_type'       => 1,
                'data_flats'      => $flat
            ];
            $villas =[ 
                'aqar_type'       => 2,
                'data_villas'     => $villa
            ];
            $buildings =[ 
                'aqar_type'       => 3,
                'data_buildings'  => $building
            ];
            $data=[
                'lands'      => $lands,
                'flats'      => $flats,
                'villas'     => $villas,
                'buildings'  => $buildings

            ];
            return $this->returnData('data',$data,trans('response_msg.data'));
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function getAqar(Request $request)
    {
        try{
            $getAqarValidate = $this->getAqarValidate($request);
            if($getAqarValidate){
                return $getAqarValidate;
            }
            if($request->aqar_type == 0){
                $land   = Land::with(['gallery' =>  function ($query){
                    $query->where('aqar_type' , 0);
                }])->find($request->aqar_id);
                if(!$land){
                    return $this->returnError('001',trans('response_msg.data_err'));
                }
                return $this->returnData('land',$land,trans('response_msg.data'));
            }
            if($request->aqar_type == 1){
                $flat   = Flat::with(['gallery' =>  function ($query){
                    $query->where('aqar_type' , 1);
                }])->find($request->aqar_id);
                if(!$flat){
                    return $this->returnError('001',trans('response_msg.data_err'));
                }
                return $this->returnData('flat',$flat,trans('response_msg.data'));
            }
            if($request->aqar_type == 2){
                $villa   = Villa::with(['gallery' =>  function ($query){
                    $query->where('aqar_type' , 2);
                }])->find($request->aqar_id);
                if(!$villa){
                    return $this->returnError('001',trans('response_msg.data_err'));
                }
                return $this->returnData('villa',$villa,trans('response_msg.data'));
            }
            if($request->aqar_type == 3){
                $building   = Building::with(['gallery' =>  function ($query){
                    $query->where('aqar_type' , 3);
                }])->find($request->aqar_id);
                if(!$building){
                    return $this->returnError('001',trans('response_msg.data_err'));
                }
                return $this->returnData('building',$building,trans('response_msg.data'));
            }
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    // 
    public function searchParents(Request $request)
    {
       try{
            $getAqarValidate = $this->getAqarValidate($request);
            if($getAqarValidate){
                return $getAqarValidate;
            }
            $search =$request->key;
            if ($search) 
            {
                $applications = Application::where('user_id',$request->user_id)->where(function($query) use ($search)
                    {   $query->where('study_year','LIKE',"%$search%")
                    ->orWhere('father_full_name','LIKE',"%$search%")
                    ->orWhere('father_national_id','LIKE',"%$search%")
                    ->orWhere('email','LIKE',"%$search%")
                    ->orWhere('mobile','LIKE',"%$search%")
                    ->orWhere('nationality','LIKE',"%$search%")
                    ->orWhere('student_name','LIKE',"%$search%")
                    ->orWhere('date_of_birth','LIKE',"%$search%")
                    ->orWhere('study_type','LIKE',"%$search%")
                    ->orWhere('place_of_birth','LIKE',"%$search%")
                    ->orWhere('student_national_id','LIKE',"%$search%")
                    ->orWhere('gender','LIKE',"%$search%");
                })->get();
            }
            if(count($applications) < 1){
                return $this->returnError('000',"عفوا لا توجد بيانات");
            }
            return $this->returnData('applications',$applications,"تم استرجاع البيانات بنجاح");
        }catch(\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function store(Request $request)
    { 
        try{
            $AqarTypeValidate = $this->AqarTypeValidate($request);
            if($AqarTypeValidate){
                return $AqarTypeValidate;
            }
            if($request->aqar_type == 0){
                $Land = $this->Land($request);
                if($Land){
                    return $Land;
                }
            }
            if($request->aqar_type == 1){
                $Flat = $this->Flat($request);
                if($Flat){
                    return $Flat;
                }
            }
            if($request->aqar_type == 2){
                $Villa = $this->Villa($request);
                if($Villa){
                    return $Villa;
                }
            }
            if($request->aqar_type == 3){
                $Building = $this->Building($request);
                if($Building){
                    return $Building;
                }
            }
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
     /*
    |--------------------------------------------------------------------------
    |  For Added New Land
    |--------------------------------------------------------------------------
    */
    public function Land($request)
    {
        $LandValidate =$this->LandValidate($request);
        if($LandValidate){
            return $LandValidate;
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
        $price =  $request->meter_price * $request->area;
        $request->request->add(['commission' => 2.5]);
        $request->request->add(['price'      => $price]); 
        $commission_value = ($request->commission / 100) * $price;
        $price_after_commission =$request->price +  $commission_value ;
        $request->request->add(['price_after_commission' =>  $price_after_commission]);
        $request->request->add(['commission_value'       =>  $commission_value]);
        $land = Land::create($request->all());
        if($request->has('photos')){
            foreach($request->photos as $photo){
                $file_path = $this->saveImage('gallery', $photo);
                Gallery::create([
                    'aqar_id' => $land->id  ,
                    'photo'   => $file_path ,
                    'aqar_type' => 0
                ]);
            }
        }
        $id = $land->id;
        $land   = Land::with(['gallery' =>  function ($query) use ($id){
            $query->where('aqar_id' ,$id)->where('aqar_type' ,0);
        }])->where('id' ,$id)->get();
        return $this->returnData('land',$land,trans('response_msg.data'));
    }
     /*
    |--------------------------------------------------------------------------
    |  For Added New Flat
    |--------------------------------------------------------------------------
    */
    public function Flat($request)
    {
        
        $FlatValidate =$this->FlatValidate($request);
        if($FlatValidate){
            return $FlatValidate;
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
        $request->request->add(['commission' => 2.5]);  
        $commission_value       = ($request->commission / 100) * $request->price;
        $price_after_commission =   $request->price +  $commission_value ;
        $request->request->add(['price_after_commission' =>  $price_after_commission]);
        $request->request->add(['commission_value'       =>  $commission_value]);
        $flat = Flat::create($request->all());
        if($request->has('photos')){
            foreach($request->photos as $photo){
                $file_path = $this->saveImage('gallery', $photo);
                Gallery::create([
                    'aqar_id' => $flat->id  ,
                    'photo'   => $file_path ,
                    'aqar_type' => 1
                ]);
            }
        }
        $id = $flat->id;
        $flat   = Flat::with(['gallery' =>  function ($query) use ($id){
            $query->where('aqar_id' ,$id)->where('aqar_type' , 1);
        }])->where('id' ,$id)->get();
        return $this->returnData('flat',$flat,trans('response_msg.data'));
    }
     /*
    |--------------------------------------------------------------------------
    |  For Added New Villa 
    |--------------------------------------------------------------------------
    */
    public function Villa($request)
    {
        
        $VillaValidate = $this->VillaValidate($request);
        if($VillaValidate){
            return $VillaValidate;
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
        $request->request->add(['commission' => 2.5]);  
        $commission_value       = ($request->commission / 100) * $request->price;
        $price_after_commission =   $request->price +  $commission_value ;
        $request->request->add(['price_after_commission' =>  $price_after_commission]);
        $request->request->add(['commission_value'       =>  $commission_value]);
        $villa = Villa::create($request->all());
        if($request->has('photos')){
            foreach($request->photos as $photo){
                $file_path = $this->saveImage('gallery', $photo);
                Gallery::create([
                    'aqar_id' => $villa->id  ,
                    'photo'   => $file_path ,
                    'aqar_type' => 2
                ]);
            }
        }
        $id = $villa->id;
        $villa   = Villa::with(['gallery' =>  function ($query) use ($id){
            $query->where('aqar_id' ,$id)->where('aqar_type' , 2);
        }])->where('id' ,$id)->get();
        return $this->returnData('villa',$villa,trans('response_msg.data'));
    }
     /*
    |--------------------------------------------------------------------------
    |  For Added New Building
    |--------------------------------------------------------------------------
    */
    public function Building($request)
    {
        
        $BuildingValidate = $this->BuildingValidate($request);
        if($BuildingValidate){
            return $BuildingValidate;
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
        $request->request->add(['commission' => 2.5]);  
        $commission_value       = ($request->commission / 100) * $request->price;
        $price_after_commission =   $request->price +  $commission_value ;
        $request->request->add(['price_after_commission' =>  $price_after_commission]);
        $request->request->add(['commission_value'       =>  $commission_value]);
        $building = Building::create($request->all());
        if($request->has('photos')){
            foreach($request->photos as $photo){
                $file_path = $this->saveImage('gallery', $photo);
                Gallery::create([
                    'aqar_id' => $building->id  ,
                    'photo'   => $file_path ,
                    'aqar_type' => 3
                ]);
            }
        }
        $id = $building->id;
        $building   = Building::with(['gallery' =>  function ($query) use ($id){
            $query->where('aqar_id' ,$id)->where('aqar_type' , 3);
        }])->where('id' ,$id)->get();
        return $this->returnData('building',$building,trans('response_msg.data'));
    }
    public function update(Request $request)
    {    
        try{
            $updateAqarTypeValidate = $this->updateAqarTypeValidate($request);
            if($updateAqarTypeValidate){
                return $updateAqarTypeValidate;
            }
            if($request->aqar_type == 0){
                $Land = $this->updateLand($request);
                if($Land){
                    return $Land;
                }
            }
            if($request->aqar_type == 1){
                $Flat = $this->Flat($request);
                if($Flat){
                    return $Flat;
                }
            }
            if($request->aqar_type == 2){
                $Villa = $this->Villa($request);
                if($Villa){
                    return $Villa;
                }
            }
            if($request->aqar_type == 3){
                $Building = $this->Building($request);
                if($Building){
                    return $Building;
                }
            }
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function destroy(Request $request)
        {
            try{
                // $beautician = Beautician::find($request->beautician_id);
                // if(!$beautician){
                //     return $this->returnError('001', trans('response_msg.user_id_err'));
                // }
                // $filePath1 = $beautician->photo;
                // if($filePath1){
                //     $url1=str_replace('http://beaut.local/','',$filePath1);
                //     if(file_exists($url1)){
                //         unlink($url1);
                //     }         
                // }   
                // $oldMethod = BeauticianPaymentMethod::where('beautician_id', $beautician->id)->get();
                // if(count($oldMethod) > 0){
                //     foreach ($oldMethod as $key) {
                //         $key->delete();
                //     }
                // } 
                // $oldPhotos= Gallery::where('beautician_id', $beautician->id)->get();
                //     foreach ($oldPhotos as $key) {
                //         $filePath1 = $key->photo;
                //         if($filePath1){
                //             $url1=str_replace('http://beaut.local/','',$filePath1);
                //             if(file_exists($url1)){
                //                 unlink($url1);
                //             }
                //         }
                //         $key->delete();
                //     }   
                //   $beautician->delete();
                //   return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
            }
            catch(\Exception $ex){
                return $this->returnError($ex->getCode(), $ex->getMessage());

            }
        }
    /*
    |--------------------------------------------------------------------------
    |  For All Request Validation To This Controller 
    |--------------------------------------------------------------------------
    */
    public  function AqarTypeValidate($request)
    {
        $rules = [
                'aqar_type'                  => "required|in:0,1,2,3",   
        ];
        $messages = [
                'aqar_type'                  => trans('validation.type'),  
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function getAqarValidate($request)
    {
        $rules = [
                'aqar_type'                  => "required|in:0,1,2,3", 
                'aqar_id'                    => 'required'  
        ];
        $messages = [
                'aqar_type'                  => trans('validation.aqar_type'), 
                'aqar_id'                    => trans('validation.aqar_id'),  
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function searchAqarValidate($request)
    {
        $rules = [
                'aqar_type'                  => "required|in:0,1,2,3",
                'address'                    => 'required'          , 
                'latitude'                   => 'required'
             ];
        $messages = [
                'aqar_type'                  => trans('validation.aqar_type'), 
                'address'                    => trans('validation.address'), 
                'latitude'                   => trans('validation.latitude'), 
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function FlatValidate($request)
    {
        $rules = [
                'city'                  => 'required|string',
                'district'              => 'required|string',
                'suk_number'            => 'required|integer',
                'suk_date'              => 'required|date_format:"d-m-Y"', 
                'address'               => 'required|string',
                'longitude'             => 'required|numeric',
                'latitude'              => 'required|numeric', 
                'area'                  => 'required|numeric', 
                'benefits_nearby'       => 'required|string',
                'interfaces_number'     => 'required|string',
                'price'                 => 'required|numeric',
                'street_type'           => 'required|numeric|in:0,1,2' ,
                'street_name'           => 'required|string',
                'floor_number'          => 'required|integer',
                'bedrooms'              => 'required|integer',
                'bathrooms'             => 'required|integer',
                'halls_number'          => 'required|integer', 
                'session_rooms'         => 'required|integer', 
                'kitchens'              => 'required|integer', 
                'maid_room'             => 'required|numeric|in:0,1',
                'driver_room'           => 'required|numeric|in:0,1',
                'indoor_parking'        => 'required|numeric|in:0,1',
                'user_id'               => 'exists:users,id',  
                'photos'                => 'required|array',
                'photos.*'                => 'mimes:png,jpeg,jpg,gif'                      
        ];
        $messages = [
                'city'                  => trans('validation.city'),
                'district'              => trans('validation.district'),
                'suk_number'            => trans('validation.suk_number'),
                'suk_date'              => trans('validation.suk_date'),
                'address.'              => trans('validation.address'),
                'longitude'             => trans('validation.longitude'),
                'latitude'              => trans('validation.latitude'),
                'area'                  => trans('validation.area'),
                'benefits_nearby'       => trans('validation.benefits_nearby'),
                'interfaces_number'     => trans('validation.interfaces_number'),
                'price'                 => trans('validation.price'),
                'street_type'           => trans('validation.street_type'),
                'street_name'           => trans('validation.street_name'),
                'floor_number'          => trans('validation.floor_number'),
                'bedrooms.'             => trans('validation.bedrooms'),
                'bathrooms'             => trans('validation.bathrooms'),
                'halls_number'          => trans('validation.halls_number'),
                'session_rooms.'        => trans('validation.session_rooms'),
                'kitchens'              => trans('validation.kitchens'),
                'maid_room'             => trans('validation.maid_room'),
                'driver_room'           => trans('validation.driver_room'),
                'indoor_parking'        => trans('validation.indoor_parking'),
                'user_id'               => trans('validation.user_id'),
                'photos'                  => trans('validation.photos'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function LandValidate($request)
    {
        $rules = [
                'city'                  => "required|string",
                'district'              => 'required|string',
                'suk_number'            => 'required|integer',
                'suk_date'              => 'required|date_format:"d-m-Y"', 
                'area'                  => 'required|numeric', 
                'longitude'             => 'required|numeric',
                'latitude'              => 'required|numeric', 
                'address'               => 'required|string',
                'street_type'           => 'required|in:0,1,2', 
                'street_view'           => 'required|numeric', 
                'interfaces_number'     => 'required|numeric',
                'meter_price'           => 'required|numeric', 
                'user_id'               => 'exists:users,id',
                'street_name'           => 'required|string' ,
                'photos'                => 'required|array',
                'photos.*'              => 'mimes:png,jpeg,jpg,gif'

        ];
        $messages = [
                'city'                    => trans('validation.city'),
                'district'                => trans('validation.district'),
                'suk_number'              => trans('validation.suk_number'),
                'suk_date'                => trans('validation.suk_date'),
                'area'                    => trans('validation.area'),
                'longitude'               => trans('validation.longitude'),
                'latitude'                => trans('validation.latitude'),
                'address'                 => trans('validation.address'),
                'street_type'             => trans('validation.street_type'),
                'street_name'             => trans('validation.street_name'),
                'street_view'             => trans('validation.street_view'),
                'interfaces_number'       => trans('validation.interfaces_number'),
                'meter_price'             => trans('validation.meter_price'),
                'user_id'                 => trans('validation.user_id'),
                'photos'                  => trans('validation.photos'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function VillaValidate($request)
    {
        $rules = [
                'city'                  => 'required|string',
                'district'              => 'required|string',
                'suk_number'            => 'required|integer',
                'suk_date'              => 'required|date_format:"d-m-Y"', 
                'address'               => 'required|string',
                'longitude'             => 'required|numeric',
                'latitude'              => 'required|numeric', 
                'land_area'             => 'required|numeric',
                'building_area'         => 'required|numeric', 
                'benefits_nearby'       => 'required|string',
                'interfaces_number'     => 'required|string',
                'price'                 => 'required|numeric',
                'street_type'           => 'required|numeric|in:0,1,2' ,
                'street_name'           => 'required|string',
                'floors_number'         => 'required|integer',
                'bedrooms'              => 'required|integer',
                'bathrooms'             => 'required|integer',
                'halls_number'          => 'required|integer', 
                'session_rooms'         => 'required|integer', 
                'kitchens'              => 'required|integer', 
                'maid_room'             => 'required|numeric|in:0,1',
                'driver_room'           => 'required|numeric|in:0,1',
                'indoor_parking'        => 'required|numeric|in:0,1',
                'user_id'               => 'exists:users,id',
                'photos'                => 'required|array',
                'photos.*'                => 'mimes:png,jpeg,jpg,gif'             
        ];
        $messages = [
                'city'                  => trans('validation.city'),
                'district'              => trans('validation.district'),
                'suk_number'            => trans('validation.suk_number'),
                'suk_date'              => trans('validation.suk_date'),
                'address.'              => trans('validation.address'),
                'longitude'             => trans('validation.longitude'),
                'latitude'              => trans('validation.latitude'),
                'land_area'             => trans('validation.land_area'),
                'building_area'         => trans('validation.building_area'),
                'benefits_nearby'       => trans('validation.benefits_nearby'),
                'interfaces_number'     => trans('validation.interfaces_number'),
                'price'                 => trans('validation.price'),
                'street_type'           => trans('validation.street_type'),
                'street_name'           => trans('validation.street_name'),
                'floors_number'         => trans('validation.floors_number'),
                'bedrooms.'             => trans('validation.bedrooms'),
                'bathrooms'             => trans('validation.bathrooms'),
                'halls_number'          => trans('validation.halls_number'),
                'session_rooms.'        => trans('validation.session_rooms'),
                'kitchens'              => trans('validation.kitchens'),
                'maid_room'             => trans('validation.maid_room'),
                'driver_room'           => trans('validation.driver_room'),
                'indoor_parking'        => trans('validation.indoor_parking'),
                'user_id'               => trans('validation.user_id'),
                'photos'                  => trans('validation.photos'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function BuildingValidate($request)
    {
        $rules = [
            'city'                  => 'required|string',
            'district'              => 'required|string',
            'suk_number'            => 'required|integer',
            'suk_date'              => 'required|date_format:"d-m-Y"', 
            'address'               => 'required|string',
            'longitude'             => 'required|numeric',
            'latitude'              => 'required|numeric',
            'land_area'             => 'required|numeric',
            'building_area'         => 'required|numeric', 
            'interfaces_number'     => 'required|string', 
            'benefits_nearby'       => 'required|string',
            'price'                 => 'required|numeric',
            'street_type'           => 'required|numeric|in:0,1,2' ,
            'street_name'           => 'required|string',
            'floors_number'         => 'required|integer',
            'garage_floor'          => 'required|in:0,1',
            'apartments_number'     => 'required|integer',
            'driver_room_number'    => 'required|integer', 
            'user_id'               => 'exists:users,id',
            'photos'                => 'required|array',
            'photos.*'                => 'mimes:png,jpeg,jpg,gif'        
        ];
        $messages = [
            'city'                  => trans('validation.city'),
            'district'              => trans('validation.district'),
            'suk_number'            => trans('validation.suk_number'),
            'suk_date'              => trans('validation.suk_date'),
            'address.'              => trans('validation.address'),
            'longitude'             => trans('validation.longitude'),
            'latitude'              => trans('validation.latitude'),
            'land_area'             => trans('validation.land_area'),
            'building_area'         => trans('validation.building_area'),
            'benefits_nearby'       => trans('validation.benefits_nearby'),
            'interfaces_number'     => trans('validation.interfaces_number'),
            'price'                 => trans('validation.price'),
            'street_type'           => trans('validation.street_type'),
            'street_name'           => trans('validation.street_name'),
            'floors_number'         => trans('validation.floors_number'),
            'garage_floor.'         => trans('validation.garage_floor'),
            'apartments_number'     => trans('validation.apartments_number'),
            'driver_room_number'    => trans('validation.driver_room_number'),
            'user_id'               => trans('validation.user_id'),
            'photos'                 => trans('validation.photos'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
}
