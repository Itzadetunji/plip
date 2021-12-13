<?php

namespace App\Services\Mono;

use App\Models\User;
use App\Models\UserWallet;
use App\Enums\UserWalletStatus;
use App\Enums\ServiceResponseType;
use Illuminate\Support\Facades\DB;
use App\Responders\ServiceResponse;
use Illuminate\Support\Facades\Cache;
use App\Notifications\WalletInformationUpdate;

class MonoService
{
    const CACHE_TIME = 60 * 10; //10minutes
    /**
     * Class constructor.
     */
    public function __construct(public MonoClient $monoClient)
    {
        $this->monoClient = $monoClient;
    }

    public function addNewWallet(User $user, string $code): ServiceResponse
    {
        $response = $this->monoClient->getExchangeToken($code);

        if ($response->status) {
            $wallet = UserWallet::updateOrCreate(
                [
                    "account_id" => $response->data->id
                ],
                [
                    "user_id" => $user->id
                ]
            );

            //race condition between webhook and here...
            switch ($wallet->status) {
                case UserWalletStatus::AVAILABLE()->value:
                    $user->notify(new WalletInformationUpdate($wallet));
                    break;

                default:
                    # code...
                    break;
            }
            return new ServiceResponse(
                true,
                ServiceResponseType::SUCCESS(),
                "success",
                ["wallet" => $wallet]
            );
        }

        return new ServiceResponse(
            false,
            ServiceResponseType::ERROR(),
            $response->message,
            $response->data
        );
    }

    public function syncWallet(User $user, UserWallet $userWallet)
    {
        $response = $this->monoClient->syncData($userWallet->account_id);

        if ($response->status) {
            $data = collect($response->data->account)->toArray();
            $data["user_id"] = $user->id;
            $data["status"] = $response->data->meta['data_status'];
            $data["connection"] = $response->data->meta['auth_method'];

            $userWallet->update($data);
            $userWallet->status = UserWalletStatus::AVAILABLE();
            $userWallet->save();

            return new ServiceResponse(
                true,
                ServiceResponseType::SUCCESS(),
                "success",
                ["wallet" => $userWallet]
            );
        }

        return new ServiceResponse(
            false,
            ServiceResponseType::ERROR(),
            $response->message,
            $response->data
        );
    }

    public function updateWallet(UserWallet $userWallet)
    {
        # code...
    }

    public function getTransactions(User $user, UserWallet $userWallet, array $filter = []): ServiceResponse
    {
        //TODO: Cache the transaction requests
        // $key = $user->id . $userWallet->account_id;
        // $client = $this->monoClient;
        // $transactions = Cache::remember($key, self::CACHE_TIME, function () use ($userWallet, $filter, $client) {
        //     return $client->getTransactions($userWallet->account_id, $filter);
        // });

        $response = $this->monoClient->getTransactions(
            $userWallet->account_id,
            $filter
        );
        if ($response->status) {
            return new ServiceResponse(
                true,
                ServiceResponseType::SUCCESS(),
                "success",
                ["transactions" => $response->data]
            );
        }

        return new ServiceResponse(
            false,
            ServiceResponseType::ERROR(),
            $response->message,
            $response->data
        );
    }
}
