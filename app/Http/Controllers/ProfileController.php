<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile(){
        return Auth::user();
    }

    public function logout(){
        return response()->json([
            'success' => 'true',
            'message' => 'Logout successful',
        ], 200)->withoutCookie('loginToken');
    }
}
