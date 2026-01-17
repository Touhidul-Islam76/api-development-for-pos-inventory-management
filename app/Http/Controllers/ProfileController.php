<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function profile(){
        $data = Auth::user();

        return new UserResource($data);
    }



    public function profileUpdate( ProfileUpdateRequest $req ){
        try{

            $user = Auth::user();
            $validate = $req->validated();
            $userData = Arr::only($validate, ['name']);
            $profileData = Arr::only($validate, ['phone', 'address']);
            $user->update($userData);
            $user->profile->update($profileData);

            return response([
                'status' => true,
                'message' => 'Profile Updated Successfully',
            ], 200);

        }catch(\Exception $e){
            Log::critical($e->getMessage() . ' ' .  $e->getFile() . ' ' . $e->getLine());
            return response([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }


    public function logout(){
        return response()->json([
            'success' => 'true',
            'message' => 'Logout successful',
        ], 200)->withoutCookie('loginToken');
    }
}
