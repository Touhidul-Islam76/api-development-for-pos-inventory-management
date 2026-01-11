<?php

// use App\Http\Controllers\InvoiceController;
// use App\Http\Controllers\LoginController;
// use App\Http\Controllers\LogoutController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\RegisterController;
// use App\Http\Controllers\ResetPasswordController;
// use App\Http\Middleware\JwtVerify;
// use Illuminate\Support\Facades\Route;


// Route::post('register', [RegisterController::class, 'register']);
// Route::post('login', [LoginController::class, 'login']);
// Route::post('resetPasswordReq', [ResetPasswordController::class, 'resetPassword']);
// Route::post('verifyOtp', [ResetPasswordController::class, 'verifyOtp']);
// Route::post('confirmPass', [ResetPasswordController::class, 'confirmPass']);


// Route::get('profile', [ProfileController::class,'profile'])->middleware(JwtVerify::class);
// Route::post('logout', [LogoutController::class,'logout'])->middleware(JwtVerify::class);


// Route::prefix('products')->group( function(){
//     Route::get('/', [ProductController::class, 'index']);
//     Route::get('/{product}', [ProductController::class, 'show']);
//     Route::post('/store', [ProductController::class, 'store']);
//     Route::put('/update/{product}', [ProductController::class, 'update']);
// } );


// Route::prefix('invoice')->group( function(){
//     Route::get('/', [InvoiceController::class, 'index']);
//     Route::post('/store', [InvoiceController::class, 'store']);
// } );
