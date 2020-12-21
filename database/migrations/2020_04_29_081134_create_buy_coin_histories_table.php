<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuyCoinHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_coin_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('address');
            $table->tinyInteger('type');
            $table->bigInteger('user_id');
            $table->decimal('coin',19,8)->default(0);
            $table->decimal('btc',19,8)->default(0);
            $table->decimal('doller',19,8)->default(0);
            $table->string('transaction_id')->nullable();
            $table->tinyInteger('status')->default(STATUS_PENDING);
            $table->tinyInteger('admin_confirmation')->default(STATUS_PENDING);
            $table->integer('confirmations')->default(0);
            $table->string('bank_sleep')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('coin_type')->nullable();
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
        Schema::dropIfExists('buy_coin_histories');
    }
}
