<?php

namespace App\Helpers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;
use JWTAuth;

class RefreshToken
{
    public static function refreshToken()
    {
        $user = Auth::user();
        $token = JWTAuth::fromUser($user);

        return $token;
    }
}