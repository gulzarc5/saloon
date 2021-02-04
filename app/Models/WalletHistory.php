<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    protected $table = 'wallet_history';
    protected $fillable = [
        'wallet_id','transaction_type','amount','total_amount','comment'
    ];
}
