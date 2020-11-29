<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    'message' => 'Akses token tidak berlaku, silahkan login kembali',
                    'isAuthorized' => false
            ], 400);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                // $token = JWTAuth::refresh($token);
                // try
                // {
                //     $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                //     $user = JWTAuth::setToken($refreshed)->toUser();
                //     header('Authorization: Bearer ' . $refreshed);
                // }
                // catch (JWTException $e)
                // {
                //     return response()->json([
                //     'code'   => 103, // means not refreshable 
                //     'response' => null // nothing to show 
                //     ]);
                // }
                return response()->json([
                    'message' => "Masa berlaku token sudah habis, harap login kembali",
                    'isAuthorized' => false
            ], 400);
            }else{
                return response()->json([
                    'message' => 'Akses token bermasalah, silahkan login',
                    'isAuthorized' => false
            ], 400);
            }
        }
        return $next($request);
    }
}
