<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundInfo extends Model
{
    protected $table = 'refund_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'order_id','amount','name','bank_name','ac_no','ifsc','branch_name','refund_status'
    ];

    public function order()
    {
        return $this->belongsTo('App\Models\User','order_id',$this->primaryKey);
    }
}
