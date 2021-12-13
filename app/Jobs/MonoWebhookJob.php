<?php

namespace App\Jobs;

use App\Enums\MonoEventType;
use App\Models\UserWallet;
use App\Notifications\WalletInformationUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    public function handle()
    {
        $data = $this->payload["data"]["account"];
        $data["status"] = $this->payload["data"]['meta']['data_status'];
        $data["connection"] = $this->payload["data"]['meta']['auth_method'];
        $data["institution"] = collect($data["institution"]);
        
        $userWallet = UserWallet::updateOrCreate(["account_id" => $data["_id"]], $data);

        switch ($this->payload["event"]) {
            case MonoEventType::UPDATED()->value:
                if (!empty($userWallet->user)) {
                    $userWallet->user->notify(new WalletInformationUpdate($userWallet));
                }
                break;

            default:
                # code...
                break;
        }
    }
}
