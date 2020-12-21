<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWalletIdAtBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_bonus_distribution_histories', function (Blueprint $table) {
            $table->bigInteger('wallet_id')->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_bonus_distribution_histories', function (Blueprint $table) {
            $table->dropColumn('wallet_id');
        });
    }
}
