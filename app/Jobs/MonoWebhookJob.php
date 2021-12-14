<?php

namespace App\Jobs;

use App\Models\UserWallet;
use App\Enums\MonoEventType;
use App\Enums\UserWalletStatus;
use Illuminate\Bus\Queueable;
use App\Services\Mono\MonoService;
use App\Models\UserWalletTransaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\WalletInformationUpdate;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MonoWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public array $payload;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MonoService $monoService)
    {
        $data = $this->payload["data"]["account"];
        $data["account_number"] = $data["accountNumber"];
        $data["connection"] = $this->payload["data"]['meta']['auth_method'];
        $data["institution"] = collect($data["institution"]);

        $userWallet = UserWallet::updateOrCreate(["account_id" => $data["_id"]], $data);

        if (!empty($userWallet)) {
            switch ($this->payload["event"]) {
                case MonoEventType::UPDATED()->value:
                    dispatch(new ProcessMonoTransactionsJob($userWallet, $this->payload["event"]));
                    break;

                default:
                    # code...
                    break;
            }
        }
    }
}
