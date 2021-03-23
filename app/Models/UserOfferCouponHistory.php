<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOfferCouponHistory extends Model
{
    protected $table = 'user_offer_coupon_histories';

    protected $fillable = [
        'customer_id','offer_id','offer_type','order_id'
    ];
}
