<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Support\Facades\Auth;

class Is_Blocked
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try{
        if( Auth::guard('users')->user()->block == 0){
            return $next($request);
           }
           return response([
            'status' => false,
            'errNum' => "E000",
            'msg'    => "عفوا حسابك معطل مؤقتا برجاء التواصل مع الاداره لتفعيل الحساب" ,
        ]);
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
