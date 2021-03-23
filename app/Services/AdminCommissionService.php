<?php
namespace App\Services;

use App\Models\AdminCommission;

class AdminCommissionService
{
    public static function commissionFetch($total_amount){
        $commission = AdminCommission::where('from_amount','<=',$total_amount)
        ->where('to_amount','>=',$total_amount)->first();
        return $commission;
    }
}