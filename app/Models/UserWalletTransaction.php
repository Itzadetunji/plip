<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\UserWallet;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWalletTransaction extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        "user_wallet_id",
        "transaction_id",
        "type",
        "amount",
        "balance",
        "narration",
        "currency",
        "category",
        "date",
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(UserWallet::class);
    }

    public function user()
    {
        $this->hasOneThrough(User::class, UserWallet::class);
    }

    // public function scopeFilter($query, $filterData)
    // {
    //     $transactions = $query->whereHas('user');

    //     if ($filter = $filterData->type) {
    //         if ($filter === 'debit') {
    //             $transactions = $transactions->where('type', 'debit');
    //         } else {
    //             $transactions = $transactions->where('type', 'credit');
    //         }
    //     }

    //     if ($filter = $filterData->category) {
    //         $category = ExpenseCategory::find($filter);

    //         if (!empty($category)) {
    //             foreach ($category->keywords as $key => $value) {
    //                 $transactions = $transactions->where('name', 'LIKE', "%{$value}%");
    //             }
    //         }
    //     }

    //     if ($filter = $filterData->narration) {
    //         $transactions = $transactions->where('narration', 'LIKE', "%{$value}%");
    //     }

    //     if ($filter = $filterData->start_from) {
    //         $transactions = $transactions->whereDate('date', '>', Carbon::parse($filter));
    //     }

    //     if ($filter = $filterData->end_to) {
    //         $transactions = $transactions->whereDate('date', '<', Carbon::parse($filter));
    //     }

    //     if ($filter = $filterData->amount) {
    //         $transactions = $transactions->where('amount', $filter);
    //     }
    // }
}
