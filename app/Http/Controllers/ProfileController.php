<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function profile()
    {
        $data = Auth::user();

        return new UserResource($data);
    }



    public function profileUpdate(ProfileUpdateRequest $req)
    {
        dd(request()->cookie('loginToken'));
        try {
            /** @var User $user */
            $user = Auth::user();
            $validate = $req->validated();

            $userData = Arr::only($validate, ['name']);
            $profileData = Arr::only($validate, ['phone', 'address']);

            // update user
            $user->update($userData);

            // update profile safely
            $profile = $user->profile;
            $profile->update($profileData);

            return response([
                'status' => true,
                'message' => 'Profile Updated Successfully',
            ], 200);
        } catch (\Exception $e) {
            // Log::critical($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            // dd($e->getMessage(), $e->getFile(), $e->getLine());
            // return response([
            //     'status' => false,
            //     'message' => 'Something went wrong',
            // ], 500);

            Log::error($e);
            return response([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Something went wrong'
            ], 500);
        }
    }



    public function logout()
    {
        return response()->json([
            'success' => 'true',
            'message' => 'Logout successful',
        ], 200)->withoutCookie('loginToken');
    }
}
