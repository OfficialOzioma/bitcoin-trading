<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_transaction_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('club_id')->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('wallet_id');
            $table->decimal('amount',13,8)->default(0);
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('membership_transaction_histories');
    }
}
