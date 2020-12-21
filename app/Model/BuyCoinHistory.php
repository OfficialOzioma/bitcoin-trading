<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BuyCoinHistory extends Model
{
    protected $fillable = ['confirmations','status','coin_type'];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
