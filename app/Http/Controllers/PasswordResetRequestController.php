<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use JWTAuth;
use Tymon\JWTAuth\Exception\JWTExceptions;

class PasswordResetRequestController extends Controller
{
    public function sendPasswordResetEmail(Request $request)
    {
        $this->checkToken();

        // If email does not exist
        if (!$this->validEmail($request->email))
        {
            return response()->json([
                'message' => 'Email does not exist.'
            ], Response::HTTP_NOT_FOUND);
        }
        else
        {
            // If email exists
            $this->sendMail($request->email);
            return response()->json([
                'messsage' => 'Check your inbox, we have sent a link to reset email.'
            ], Response::HTTP_OK);
        }
    }

    public function sendMail($email)
    {
        $token = $this->generateToken($email);
        Mail::to($email)->send(new SendMail($token));
    }

    public function validEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    public function generateToken($email)
    {
        $isOtherToken = DB::table('recover_password')->where('email', $email)->first();

        if ($isOtherToken)
        {
            return $isOtherToken->token;
        }

        $token = Str::random(80);;
        $this->storeToken($token, $email);
        return $token;
    }

    public function storeToken($token, $email)
    {
        DB::table('recover_password')->insert([
            'email' => $email,
            'token' => $token,
            'created' => Carbon::now()
        ]);
    }

    protected function checkToken()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }
    }
}
