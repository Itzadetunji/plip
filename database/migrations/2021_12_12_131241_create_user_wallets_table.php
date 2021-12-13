<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid("user_id")->nullable()->constrained("users");
            $table->string("account_id");
            $table->string("name")->nullable();
            $table->string("type")->nullable();
            $table->string("currency")->nullable();
            $table->string("accountNumber")->nullable();
            $table->double("balance")->nullable();
            $table->string("bvn")->nullable();
            $table->json("institution")->nullable();
            $table->string("status")->default("NOT AVAILABLE");
            $table->string("connection")->nullable();
            $table->timestamp("last_synced")->nullable();
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
        Schema::dropIfExists('user_wallets');
    }
}
