<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register( RegisterRequest $req )
    {
        try{

            $validate = $req->validated();

            $userData = Arr::only( $validate, [ 'name', 'email', 'password', 'role' ] );
            $profileData = Arr::only( $validate, [ 'phone', 'address' ] );

            $user = User::create( $userData );

            $profileData['user_id'] = $user->id;

            if( $req->hasFile('avatar') ){
                $avatarPath = $req->file('avatar')->store('avatars', 'public');
                $profileData['avatar'] = $avatarPath;
            }

            Profile::create( $profileData );

            return response()->json([

                'status' => true,
                'message' => 'User registered successfully and profile created',

            ],201);

        }catch(\Exception $e){

            Log::critical($e->getMessage().''.$e->getFile().''.$e->getLine());  //here log means saving error message in laravel log file(storage/logs/laravel.log) and critical means error level & it has more level like ( emergency, alert, error, warning, notice, info, debug )

            return response()->json([

                'status' =>false,
                'message' => 'Something went wrong',

            ],500);

        }
    }
}
