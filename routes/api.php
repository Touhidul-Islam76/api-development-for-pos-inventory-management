<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;


Route::post('register', [RegisterController::class, 'register']);
Route::post('resetPassword', [ResetPasswordController::class, 'resetPassword']);
