<?php

namespace App\Http\Controllers\Api\Beaut;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    use GeneralTrait;
    private $lang;   
    public function __construct(Request $request)
   {
       App::setlocale($request->lang);
       $this->lang= app()->getLocale();
   }
   public function GetBeauticianGallery()
   {
       try{
               $beautician  = Auth::guard('beautician')->user();
               $gallery     = Gallery::where('beautician_id',$beautician->id)->get();
               if(count($gallery) < 1){
                   return $this->returnError('E002',trans('response_msg.gallery_err') );
               }
               return $this->returnData('gallery',$gallery,trans('response_msg.data'));
           }catch (\Exception $ex){
               return $this->returnError($ex->getCode(), $ex->getMessage());
           }
   }
   public function store(Request $request)
   {
       try{
               $galleryValidate =$this->galleryValidate($request);
               if($galleryValidate){
                   return $galleryValidate;
               } 
               if($request->has('beautician_id')){
                $beautician_id  = $request->beautician_id;
               }else{
                $beautician = Auth::guard('beautician')->user();
                $beautician_id  = $beautician->id;
               }
               if($request->has('photos')){
                foreach ($request->photos as $photo) {
                    $file_path=$this->saveImage('gallery',$photo);
                    Gallery::create([
                        'photo'         =>  $file_path,
                        'beautician_id' =>  $beautician_id,
                    ]);
                }
            }
            $gallery = Gallery::where('beautician_id' , $beautician_id)->get() ;
            return $this->returnData('gallery',$gallery,trans('response_msg.save'));
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
   }
   public function update(Request $request)
   {
       try{
               $updateValidation =$this->updateValidate($request);
               if($updateValidation){
                   return $updateValidation;
               }
               if($request->has('beautician_id')){
                $beautician_id  = $request->beautician_id;
               }else{
                $beautician = Auth::guard('beautician')->user();
                $beautician_id  = $beautician->id;
               }
               $oldGallery = Gallery::where('beautician_id' , $beautician_id)->get();
               if(!$oldGallery){
                   return $this->returnError('E002',trans('response_msg.gallery_err') );
               }
               if($oldGallery){
                   foreach ($oldGallery as $old) {
                       $filePath1 = $old->photo;
                       if($filePath1){
                            $url1=str_replace('http://beaut.local/','',$filePath1);
                            if(file_exists($url1)){
                                unlink($url1);
                            }
                        }
                        $old->delete();
                    }
                }
                if($request->has('photos')){
                    foreach ($request->photos as $photo) {
                        $file_path=$this->saveImage('gallery',$photo);
                        Gallery::create([
                            'photo'         =>  $file_path,
                            'beautician_id' =>  $beautician_id,
                        ]);
                    }
                }
                $gallery = Gallery::where('beautician_id' , $beautician_id)->get() ;
               return $this->returnData('gallery',$gallery,trans('response_msg.update'));
            }catch (\Exception $ex){
                 return $this->returnError($ex->getCode(), $ex->getMessage());
            }
   }
   public function destroy(Request $request)
   {
       try{
               $updateValidation =$this->updateValidate($request);
               if($updateValidation){
                   return $updateValidation;
               } 
               if($request->has('beautician_id')){
                $beautician_id  = $request->beautician_id;
               }else{
                $beautician = Auth::guard('beautician')->user();
                $beautician_id  = $beautician->id;
               }
               $oldGallery = Gallery::where('beautician_id' , $beautician_id)->get();
               if(count($oldGallery) < 1){
                   return $this->returnError('E002',trans('response_msg.gallery_err') );
               }
               if($oldGallery){
                   foreach ($oldGallery as $old) {
                        $filePath1 = $old->photo;
                        if($filePath1){
                         $url1=str_replace('http://beaut.local/','',$filePath1);
                         if(file_exists($url1)){
                             unlink($url1);
                         }
                     }
                     $old->delete();
                     return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
                 }
              }
              
            }catch (\Exception $ex){
                 return $this->returnError($ex->getCode(), $ex->getMessage());
            }
   }
   public function deleteOnePhoto(Request $request)
   {
       try{
               $photoValidate =$this->photoValidate($request);
               if($photoValidate){
                   return $photoValidate;
               } 
               if($request->has('beautician_id')){
                $beautician = Auth::guard('beautician')->user();
                $beautician_id  = $request->beautician_id;
               }else{
                $beautician = Auth::guard('beautician')->user();
                $beautician_id  = $beautician->id;
               }
               $oldGallery = Gallery::where('id',$request->photo_id)->where('beautician_id' , $beautician_id)->get();
               if(count($oldGallery) < 1){
                   return $this->returnError('E002',trans('response_msg.gallery_err') );
               }
               if($oldGallery){
                   foreach ($oldGallery as $old) {
                        $filePath1 = $old->photo;
                        if($filePath1){
                         $url1=str_replace('http://beaut.local/','',$filePath1);
                         if(file_exists($url1)){
                             unlink($url1);
                         }
                     }
                     $old->delete();
                     return $this->returnSuccessMessage(trans('response_msg.delete'),'S000');
                 }
              }
              
            }catch (\Exception $ex){
                 return $this->returnError($ex->getCode(), $ex->getMessage());
            }
   }


    public  function galleryValidate($request)
    {
        $rules = [
                'photos'                =>'required|array',
                'photos.*'              =>'mimes:jpg,jpeg,png,gif'         
        ];
        $messages = [
                'photos'           => trans('validation.photos'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function updateValidate($request)
    {
        $rules = [
                'beautician_id'         =>'exists:beauticians,id',
                'photos'                =>'array',
                'photos.*'              =>'mimes:jpg,jpeg,png,gif',
        ];
        $messages = [
                'beautician_id'         => trans('validation.beautician_id'),
                'photos'           => trans('validation.photos'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    public  function photoValidate($request)
    {
        $rules = [
                'beautician_id'           =>'exists:beauticians,id',
                'photo_id'                =>'required|exists:gallery,id',
        ];
        $messages = [
                'beautician_id'           => trans('validation.beautician_id'),
                'photo_id'                => trans('validation.photo_id'),

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
    }
    
}
