<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipBonusDistributionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_bonus_distribution_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('plan_id');
            $table->bigInteger('membership_id');
            $table->date('distribution_date');
            $table->decimal('bonus_amount',13,8)->default(0);
            $table->decimal('plan_current_bonus',13,8)->default(0);
            $table->tinyInteger('bonus_type')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('membership_bonus_distribution_histories');
    }
}
