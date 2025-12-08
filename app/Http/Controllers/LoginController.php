<?php

namespace App\Http\Controllers;

use App\Helpers\JwtToken;
use App\Http\Requests\LoginReq;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login( LoginReq $req )
    {
        $user = User::where('email', $req->email)->first();
        if(!Hash::check($req->password, $user->password)){
            return response()->json([

                'error' => 'true',
                'message' => 'Invalid credentials',

            ], 401);
        }


        $userData =[
            'id' => $user->id,
            'email' => $user->email,
            ];

        $exp = time() + 60*60*24; // Token valid for 24 hours

        $token = JwtToken::createToken( $userData, $exp );
        return response()->json([

            'success' => 'true',
            'message' => 'Login successful',

        ], 200)->cookie('loginToken', $token['token'], 60*24); //setting cookie for 24 hours
    }
}
