<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offer';
    protected $fillable = [
        'name','category_id','sub_category_id','third_category_id','range_type','from_date','to_date','total_user','offer_received_user','description','image','status'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\JobCategory','category_id','id');
    }
    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory','sub_category_id','id');
    }
    public function ThirdCategory()
    {
        return $this->belongsTo('App\Models\ThirdLevelCategory','third_category_id','id');
    }
}
