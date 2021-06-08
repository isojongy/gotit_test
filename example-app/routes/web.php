<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['verify' => false, 'login' => true, 'register' => false, 'reset' => false]);

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/change-password', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('changePassword');
Route::put('/change-password', [App\Http\Controllers\HomeController::class, 'putChangePassword'])->name('putChangePassword');

Route::prefix('gift')->group(function () {
    Route::get('/redeem-point', [App\Http\Controllers\GiftController::class, 'redeemPoint'])->name('gift.redeemPoint');
    Route::post('/redeem-point', [App\Http\Controllers\GiftController::class, 'postRedeemPoint'])->name('gift.postRedeemPoint');
    Route::get('/spin-gift', [App\Http\Controllers\GiftController::class, 'spinGift'])->name('gift.spinGift');
    Route::post('/spin-gift', [App\Http\Controllers\GiftController::class, 'postSpinGift'])->name('gift.postSpinGift');
    Route::get('/list', [App\Http\Controllers\GiftController::class, 'list'])->name('gift.list');
});
