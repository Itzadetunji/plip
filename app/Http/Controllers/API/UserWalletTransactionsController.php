<?php

namespace App\Http\Controllers\API;

use App\Models\UserWallet;
use Illuminate\Http\Request;
use App\Responders\ApiResponse;
use App\Services\Mono\MonoService;
use App\Http\Controllers\Controller;
use App\Models\UserWalletTransaction;
use App\Http\Requests\TransactionFilterRequest;

class UserWalletTransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function latestTransactions(UserWallet $userWallet)
    {
        $this->authorize("view", $userWallet);
        // using this method vs lazyLoading
        $transactions = UserWalletTransaction::where("user_wallet_id", $userWallet->id)->take(10)->get();
        return ApiResponse::success("success", ["transactions" => $transactions]);
    }

    public function getTransactions(TransactionFilterRequest $request, UserWallet $userWallet, MonoService $monoService)
    {
        $this->authorize("view", $userWallet);

        return ApiResponse::success("success", ["transactions" => $userWallet->getAllTransactions($request)]);
    }
}
