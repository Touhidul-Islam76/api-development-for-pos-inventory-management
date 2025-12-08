<?php

namespace App\Http\Controllers;

use App\Helpers\JwtToken;
use App\Http\Requests\ConfirmPassReq;
use App\Http\Requests\ResetPassOtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    public function resetPassword(ResetPasswordRequest $req)
    {
        try {

            $otp = mt_rand(100000, 999999); // Generate a 6-digit OTP

            Otp::create([
                'email' => $req->email,
                'otp' => $otp,
            ]);

            Mail::to($req->email)->send(new ResetPasswordMail($otp));

            return response()->json([

                'success' => 'true',
                'message' => 'OTP sent to your email',

            ], 200);
        } catch (\Exception $e) {

            Log::critical($e->getMessage() . '' . $e->getFile() . '' . $e->getLine());  //here log means saving error message in laravel log file(storage/logs/laravel.log) and critical means error level & it has more level like ( emergency, alert, error, warning, notice, info, debug )

            return response()->json([

                'error' => 'true',
                'message' => 'Something went wrong',

            ], 500);
        }
    }



    public function verifyOtp(ResetPassOtpRequest $req)
    {
        try {

            Otp::where('email', $req->email)
                ->where('otp', $req->otp)
                ->update(['status' => true]);   // Mark OTP as used by setting status to true

            $exp = time() + 60 * 60; // Token valid for 60 minutes
            $token = JwtToken::createToken(['email' => $req->email], $exp); //generating token with user email and expire time(60 minutes)

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
            ])->cookie('reset_password_token', $token['token'], 60); // Setting token in cookie valid for 60 minutes


        } catch (\Exception $e) {

            Log::critical($e->getMessage() . '' . $e->getFile() . '' . $e->getLine());  //here log means saving error message in laravel log file(storage/logs/laravel.log) and critical means error level & it has more level like ( emergency, alert, error, warning, notice, info, debug )

            return response()->json([

                'error' => 'true',
                'message' => 'Something went wrong',

            ], 500);
        }
    }


    public function confirmPass(ConfirmPassReq $req)
    {
        // Implementation for confirming new password goes here
        if(!$req->cookie('reset_password_token')){
            return response()->json([

                'error' => 'true',
                'message' => 'Unauthorized access - token missing',

            ], 401);
        }


        $decode = JwtToken::verifyToken( $req->cookie('reset_password_token') ); //verifying token from cookie
        if( $decode['error'] == 'true' ){

            return response()->json([

                'error' => 'true',
                'message' => $decode['error'],

            ], 401);

        }

        $user = User::where('email', $decode['payload']->email)->first();
        $user->password = $req->password ;
        $user->save();

        return response()->json([

            'success' => 'true',
            'message' => 'Password updated successfully',

        ], 200)->withoutCookie('reset_password_token'); //deleting cookie after password reset
    }
}
