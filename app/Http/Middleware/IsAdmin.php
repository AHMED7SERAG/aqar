<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if(Auth::guard('admins')->user()){
         return $next($request);
        }
        return response([
            'status' => false,
            'errNum' => "E000",
            'msg'    =>"عفوا غير مسموح لك للدخول على هذه الصفحة" ,
        ]);
    }
}
