<?php

use App\Models\UserWallet;
use Illuminate\Http\Request;
use App\Services\Mono\MonoClient;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Notifications\WalletInformationUpdate;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\UserWalletsController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\EmailVerificationController;
use App\Http\Controllers\API\ExpenseCategoryController;
use App\Http\Controllers\API\UserWalletTransactionsController;

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
Route::middleware("auth:sanctum")->group(function () {
    //channel broadcast routes
    Broadcast::routes();
    Route::apiResource("expense_categories", ExpenseCategoryController::class)->only('index');
    Route::prefix('wallets')->name('wallets.')->group(function () {
        Route::get('/', [UserWalletsController::class, "index"])->name("index");
        Route::get('/{userWallet:account_id}', [UserWalletsController::class, "show"])->name("show");

        Route::post('/connect', [UserWalletsController::class, "connect"])->name("connect");
        Route::get('/{userWallet:account_id}/sync', [UserWalletsController::class, "sync"])->name("sync");
        Route::get('/{userWallet:account_id}/transactions/latest', [UserWalletTransactionsController::class, "latestTransactions"])->name("transactions.latest");
        Route::get('/{userWallet:account_id}/transactions', [UserWalletTransactionsController::class, "getTransactions"])->name("transactions.all");
    });
});
