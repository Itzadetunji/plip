<?php

use App\Http\Controllers\API\Auth\EmailVerificationController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', RegisterController::class)->name('user.register');
    Route::post('login', LoginController::class)->name('user.login');
    Route::post('logout', LogoutController::class)->name('user.logout');
    Route::post('email', [EmailVerificationController::class, 'sendEmailToken'])->middleware(['throttle:email_verification'])->name('email.token');
    Route::post('email/verify', [EmailVerificationController::class, 'verifyEmail'])->name('email.verify');
});
