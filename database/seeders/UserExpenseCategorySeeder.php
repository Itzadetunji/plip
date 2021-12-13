<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserExpenseCategorySeeder extends Seeder
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
    }
}
