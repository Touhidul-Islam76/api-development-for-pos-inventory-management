<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\JwtVerify;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Frontend Routes

Route::get('/', [PageController::class, 'index']);
Route::get('/register', [PageController::class, 'registration'])->name('register');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/reset-password', [PageController::class, 'resetPassword'])->name('reset-password');
Route::get('/send-otp', [PageController::class, 'sendOtp'])->name('forgot-password.send-otp');
Route::get('/verify-otp', [PageController::class, 'verifyOtp'])->name('forgot-password.verify-otp');

Route::group(['middleware' => "jwt"], function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    Route::group(["prefix" => "Products"], function () {
        Route::get('/list', [ProductController::class, 'productList'])->name('products.list');
        Route::get('/add', [PageController::class, 'addProduct'])->name('products.add');
    });
});



// Backend
Route::group(['prefix' => 'backend'], function () {

    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::post('resetPasswordReq', [ResetPasswordController::class, 'resetPassword']);
    Route::post('verifyOtp', [ResetPasswordController::class, 'verifyOtp']);
    Route::post('confirmPass', [ResetPasswordController::class, 'confirmPass']);

    Route::group(['middleware' => "jwt"], function () {
        Route::get('profile', [ProfileController::class, 'profile']);
        Route::post('profile-update',[ProfileController::class,'profileUpdate']);
        Route::post('logout', [ProfileController::class, 'logout']);
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/list', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::put('/update/{product}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::get('/edit/{product}', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::post('/customer/order', [ProductController::class, 'customerOrder'])->name('customer.order');
        Route::get('/customer/confirmedOrder', [ProductController::class, 'confirmedOrder'])->name('customer.confirmedOrder');
        // customer order lists route for admin
        Route::get('/customer/apporveOrders', [ProductController::class, 'adminApprovedOrders'])->name('approvedOrders');
    })->middleware(JwtVerify::class);


    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']);
        Route::post('/store', [InvoiceController::class, 'store']);
    })->middleware(JwtVerify::class);
});
