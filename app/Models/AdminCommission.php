<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminCommission extends Model
{
    protected $table = 'admin_commissions';
    protected $fillable = [
        'from_amount','to_amount','charge_amount'
    ];
}
