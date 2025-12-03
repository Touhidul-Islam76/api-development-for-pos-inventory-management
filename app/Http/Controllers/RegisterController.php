<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register( RegisterRequest $req )
    {
        try{

            User::create($req->validated());

            return response()->json([

                'success' => 'true',
                'message' => 'User registered successfully',

            ],201);

        }catch(\Exception $e){

            Log::critical($e->getMessage().''.$e->getFile().''.$e->getLine());  //here log means saving error message in laravel log file(storage/logs/laravel.log) and critical means error level & it has more level like ( emergency, alert, error, warning, notice, info, debug )

            return response()->json([

                'error' =>'true',
                'message' => 'Something went wrong',

            ],500);

        }
    }
}
