<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionFilterRequest;
use App\Models\UserWallet;
use App\Responders\ApiResponse;
use App\Services\Mono\MonoService;
use Illuminate\Http\Request;

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

    public function latestTransactions(UserWallet $userWallet, MonoService $monoService)
    {
        $this->authorize("view", $userWallet);

        return $monoService->getTransactions(
            request()->user(),
            $userWallet,
            [
                "limit" => 20,
                "paginated" => false,
            ]
        )->toHttpResponse();
    }

    public function getTransactions(TransactionFilterRequest $request, UserWallet $userWallet, MonoService $monoService)
    {
        $this->authorize("view", $userWallet);

        return $monoService->getTransactions(
            $request->user(),
            $userWallet,
            array_filter($request->validated())
        )->toHttpResponse();
    }
}
