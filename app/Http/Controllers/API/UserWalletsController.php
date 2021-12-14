<?php

namespace App\Http\Controllers\API;

use App\Models\UserWallet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Responders\ApiResponse;
use App\Services\Mono\MonoService;
use App\Http\Controllers\Controller;
use App\Models\UserWalletTransaction;
use Illuminate\Support\LazyCollection;
use App\Jobs\ProcessMonoTransactionsJob;
use App\Http\Requests\TransactionFilterRequest;
use App\Models\ExpenseCategory;

class UserWalletsController extends Controller
{
    public function index()
    {
        $wallets = UserWallet::get();

        return ApiResponse::success("success", ["wallets" => $wallets]);
    }

    public function show(UserWallet $userWallet)
    {
        return ApiResponse::success("success", ["wallet" => $userWallet]);
    }

    public function connect(Request $request, MonoService $monoService)
    {
        return $monoService->addNewWallet(
            $request->user(),
            $request->code
        )->toHttpResponse();
    }

    public function sync(UserWallet $userWallet, MonoService $monoService)
    {
        $this->authorize("view", $userWallet);

        $response = $monoService->syncWallet(request()->user(), $userWallet);

        return $response->toHttpResponse();
    }
}
