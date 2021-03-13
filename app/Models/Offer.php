<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offer';
    protected $fillable = [
        'name','category_id','sub_category_id','third_category_id','range_type','from_date','to_date','total_user','offer_received_user','description','image','status'
    ];
}
