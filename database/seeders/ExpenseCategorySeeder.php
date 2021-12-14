<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Truncates table in the database first
        Schema::disableForeignKeyConstraints();

        DB::table('expense_categories')->truncate();

        Schema::enableForeignKeyConstraints();

        ExpenseCategory::insert([
            [
                'name' => 'Food',
                'keywords' => collect(["food", "kfc", "bukka", "turkey", "meat", "shawarma", "restaur"]),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Fares & Travel',
                'keywords' => collect(["gig", "cars", "travel", "transport", "flights", "airline"]),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Loans',
                'keywords' => collect(["carbon", "specta", "loan", "loans", "repayment"]),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Health',
                'keywords' => collect(["drugs", "health", "healthplus", "med", "medplus"]),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'income',
                'keywords' => collect(["salary"]),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Fun & Entertainments',
                'keywords' => collect([
                    "cinema", "film", "movie", "beach", "club", "hotel", "spotify", "youtube", "apple", "music", "song", "dstv", "tv", "gotv", "netflix", "HBO", "amazon prime"
                ]),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ],
            [
                'name' => 'Miscellaneous',
                'keywords' => collect([]),
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ]
        ]);
    }
}
