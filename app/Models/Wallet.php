<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = 'wallets';
    protected $fillable = [
        'user_id','amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Customer','user_id','id');
    }

    public function history()
    {
        return $this->hasMany('App\Models\WalletHistory','wallet_id','id')->latest();
    }
}
