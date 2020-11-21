<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
   
    use Exception;
use Tymon\JWTAuth\Facades\JWTAuth ;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\JWT;

class JwtMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return $this->returnError('T001', trans('response_msg.token_invalid'));
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return $this->returnError('T002',trans('response_msg.token_expired'));
            }else{
                return $this->returnError('T003' ,trans('response_msg.token_notFound'));
            }
        }
        return $next($request);
    }
}
