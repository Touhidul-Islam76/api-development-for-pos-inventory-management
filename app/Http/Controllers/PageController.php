<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){
        return view('pages.home');
    }

    public function login(){
        return view('pages.auth.login');
    }

    public function registration(){
        return view('pages.auth.register');
    }

    public function sendOtp(){
        return view('pages.auth.send-otp-page');
    }

    public function verifyOtp(Request $request){
        return view('pages.auth.verify-otp-page');
    }

    public function resetPassword(Request $request){
        return view('pages.auth.reset-pass-page');
    }

    public function dashboard(Request $request){
        return view('pages.dashboard.dashboard-page');
    }

    public function profile(Request $request){
        return view('pages.dashboard.profile-page');
    }
}
