<?php

namespace App\Models;

use App\Models\UserWallet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWalletTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(UserWallet::class);
    }

    public function user()
    {
        $this->hasOneThrough(User::class, UserWallet::class);
    }
}
