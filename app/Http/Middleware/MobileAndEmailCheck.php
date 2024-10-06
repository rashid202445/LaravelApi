<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MobileAndEmailCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(is_null(authApi()->user()->email_verified_at) ||is_null(authApi()->user()->mobile_verified_at)){
            $data=[];
            $data['need_email_verified']=authApi()->user()->email_verified_at!=null;
            $data['need_mobile_verified']=authApi()->user()->mobile_verified_at!=null;
            return res_data($data);
        }
        return $next($request);
    }
}
