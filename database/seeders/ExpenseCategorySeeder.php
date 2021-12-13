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

        DB::table('user_expense_categories')->truncate();

        Schema::enableForeignKeyConstraints();

        ExpenseCategory::insert([
            ['name' => 'Food', 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['name' => 'Travel', 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['name' => 'Loans', 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['name' => 'Health', 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()],
            ['name' => 'Miscellaneous ', 'updated_at' => Carbon::now(), 'created_at' => Carbon::now()]
        ]);
    }
}
