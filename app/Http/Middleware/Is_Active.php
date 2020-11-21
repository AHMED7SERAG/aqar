<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Is_Active
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
       if( Auth::guard('users')->email_verified == 1){
         return $next($request);
        }
        return response([
            'status' => false,
            'errNum' => "E000",
            'msg'    =>" عفوا يرجى تفعيل الحساب اولا حتى يمكنك المتابعة" ,
        ]);
    }
}
