<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MembershipClub extends Model
{
    protected $fillable = ['user_id', 'plan_id', 'amount', 'start_date', 'end_date', 'status'];

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class,'plan_id');
    }
}
