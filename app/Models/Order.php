<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'customer_id','vendor_id','customer_address_id','amount','advance_amount','payment_id','payment_request_id','payment_status','order_status','service_time','refund_request','vendor_cancel_status',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id','id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client','vendor_id','id');
    }
    public function services()
    {
        return $this->hasMany('App\Models\OrderJobs','order_id','id');
    }

}
