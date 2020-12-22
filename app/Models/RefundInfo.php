<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundInfo extends Model
{
    protected $table = 'refund_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id','account_id','refund_status','amount'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id',$this->primaryKey);
    }
    public function account()
    {
        return $this->belongsTo('App\Models\UserBankAccount','account_id',$this->primaryKey);
    }
}
