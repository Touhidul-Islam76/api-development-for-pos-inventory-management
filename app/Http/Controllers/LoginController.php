<?php

namespace App\Http\Controllers;

use App\Helpers\JwtToken;
use App\Http\Requests\LoginReq;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(LoginReq $req)
    {
        try {
            $user = User::where('email', $req->email)->first();
            if (!Hash::check($req->password, $user->password)) {
                return response()->json([

                    'error' => true,
                    'message' => 'Invalid credentials',

                ], 401);
            }


            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->profile->avatar_url,
            ];

            $exp = time() + 60 * 60 * 24; // Token valid for 24 hours

            $token = JwtToken::createToken($userData, $exp);
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $userData,
            ], 200)
                ->cookie(
                    'loginToken',      // Cookie name
                    $token['token'],   // JWT token
                    60 * 24,             // Expire in minutes (24 hours)              // HttpOnly (true = JS cannot access)
                );
        } catch (\Exception $e) {
            Log::critical(
                $e->getMessage() . ' | ' . $e->getFile() . ' | ' . $e->getLine()
            );

            return response()->json([
                'status'  => false,
                'message' => 'Server error. Please try again later.',
            ], 500);
        }
    }
}
