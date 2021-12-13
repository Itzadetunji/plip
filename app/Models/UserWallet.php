<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserWallet extends Model
{
    use Searchable;

    protected $fillable = [

        "user_id",
        "account_id",
        "name",
        "type",
        "currency",
        "accountNumber",
        "balance",
        "bvn",
        "institution",
        "status",
        "connection",
        "last_synced",
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(UserWalletTransaction::class, "wallet_id", "account_id");
    }

    public function weeklyMetrics()
    {
        // $transactions = $this->transactions()
    }
}
