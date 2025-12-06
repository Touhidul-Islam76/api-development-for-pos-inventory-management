<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordMail;
use App\Models\Otp;
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
}
