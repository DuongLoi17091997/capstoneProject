<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\sessionTokenUser;


class AuthCheckWithToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = apache_request_headers();
        $token = $header['token'];
        $findToken = sessionTokenUser::where('token', $token)->first();
        if(empty($token)){
            return response()->json(['code'=>'401','Message' => 'Invalid Token'], 401);
        }else if(empty($findToken)){
            return response()->json(['code'=>'401','Message' => 'Invalid Token'], 401);
        }else{
            return $next($request);
        }
    }
}
