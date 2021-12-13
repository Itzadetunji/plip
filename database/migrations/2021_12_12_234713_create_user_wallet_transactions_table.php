<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_wallets_id")->nullable()->index()->constrained("user_wallets");
            $table->string("wallet_id")->index();
            $table->string("type");
            $table->double("amount");
            $table->double("balance");
            $table->string("narration");
            $table->string("currency")->nullable();
            $table->string("category")->nullable();
            $table->timestamp("date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
}
