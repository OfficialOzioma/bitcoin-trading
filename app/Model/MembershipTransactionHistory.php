<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MembershipTransactionHistory extends Model
{
    protected $fillable = ['user_id', 'club_id', 'wallet_id', 'amount', 'type', 'status'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class,'wallet_id');
    }
}
