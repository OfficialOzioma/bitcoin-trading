<?php

use App\Model\MembershipPlan;
use Illuminate\Database\Seeder;

class MembershipPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MembershipPlan::create(['plan_name'=>'Silver','duration'=> 30, 'amount' => 500, 'bonus_type'=> PLAN_BONUS_TYPE_PERCENTAGE, 'bonus' => 2]);
        MembershipPlan::create(['plan_name'=>'Gold','duration'=> 30, 'amount' => 1000,'bonus_type'=> PLAN_BONUS_TYPE_PERCENTAGE, 'bonus' => 5]);
        MembershipPlan::create(['plan_name'=>'Platinum','duration'=> 30, 'amount' => 2000,'bonus_type'=> PLAN_BONUS_TYPE_PERCENTAGE, 'bonus' => 10]);
    }
}
