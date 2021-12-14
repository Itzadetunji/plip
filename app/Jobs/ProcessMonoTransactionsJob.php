<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserWallet;
use App\Enums\MonoEventType;
use Illuminate\Bus\Queueable;
use App\Enums\UserWalletStatus;
use App\Services\Mono\MonoService;
use App\Models\UserWalletTransaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\WalletInformationUpdate;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Queue\Middleware\WithoutOverlapping;

use function PHPUnit\Framework\isEmpty;

class ProcessMonoTransactionsJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public UserWallet $userWallet;

    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UserWallet $userWallet, public string $event)
    {
        $this->userWallet = $userWallet;
    }

    public function middleware()
    {
        return [new WithoutOverlapping($this->userWallet->id)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MonoService $monoService)
    {
        if (is_null($this->userWallet->user_id)) {
            throw new Exception("{$this->userWallet->id} has an empty user_id");
            $this->release(30);
            return;
        }

        if ($this->attempts() <= 5) {
            $user = $this->userWallet->user;
            $userWallet = $this->userWallet;

            $transactions = $monoService->getTransactions(
                $user,
                $this->userWallet,
                ["paginate" => "false"]
            );

            if ($transactions) {
                $walletTransactionsCount = $transactions->data['transactions']->count;

                collect($transactions->data['transactions']->data)
                    ->lazy()
                    ->map(function ($transaction) use ($userWallet) {
                        $data = $transaction;
                        $data->transaction_id = $transaction->_id;
                        $data->user_wallet_id = $userWallet->id;
                        $data->date = Carbon::parse($data->date);
                        unset($data->_id);
                        unset($data->accountNumber);

                        return collect($data);
                    })
                    // ->chunk(20)
                    ->each(function ($transaction) {
                        $data = collect($transaction)->toArray();
                        // UserWalletTransaction::insert($dbData->values()->toArray());
                        UserWalletTransaction::updateOrCreate(
                            [
                                "transaction_id" => $transaction['transaction_id']
                            ],
                            $data
                        );
                    });
                $totalCredits = collect($transactions->data['transactions']->data)->where("type", "credit")->count();
                $totalDebits = collect($transactions->data['transactions']->data)->where("type", "debit")->count();

                $userWallet->status = UserWalletStatus::AVAILABLE();
                $userWallet->transactions_count = $walletTransactionsCount;
                $userWallet->total_credits = $totalCredits;
                $userWallet->total_debits = $totalDebits;
                $userWallet->save();
                # code...

                $user->notify(new WalletInformationUpdate($userWallet));
            }
        }
    }
}
