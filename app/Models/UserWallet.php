<?php

namespace App\Models;

use Carbon\Carbon;
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
        "account_number",
        "balance",
        "bvn",
        "institution",
        "status",
        "connection",
        "last_synced",
        "total_credits",
        "total_debits",
        "transactions_count",
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(UserWalletTransaction::class);
    }


    public function scopeGetAllTransactions($query, $filterData): object
    {
        $transactions = UserWalletTransaction::where("user_wallet_id", $this->id);

        if ($filter = $filterData->type) {
            if ($filter === 'debit') {
                $transactions = $transactions->where('type', 'debit');
            } else {
                $transactions = $transactions->where('type', 'credit');
            }
        }

        if ($filter = $filterData->category) {
            $category = ExpenseCategory::find($filter);

            if (!empty($category)) {
                // foreach ($category->keywords as $key => $value) {
                //     $transactions = $transactions->orWhere('narration', 'LIKE', "%{$value}%");
                // }
                $transactions = $transactions->where('narration', 'LIKE', "%{$category->keywords[0]}%");
                for ($i = 1; $i < count($category->keywords); $i++) {
                    $transactions = $transactions->orWhere('narration', 'LIKE', "%{$category->keywords[$i]}%");
                }
            }
        }

        if ($filter = $filterData->narration) {
            $transactions = $transactions->where('narration', 'LIKE', "%{$filter}%");
        }

        if ($filter = $filterData->start_from) {
            $transactions = $transactions->whereDate('date', '>', Carbon::parse($filter));
        }

        if ($filter = $filterData->end_to) {
            $transactions = $transactions->whereDate('date', '<', Carbon::parse($filter));
        }

        if ($filter = $filterData->amount) {
            $transactions = $transactions->where('amount', $filter);
        }

        $paginate = $filterData->limit ?? 10;

        return $transactions->paginate($paginate);
    }
}
