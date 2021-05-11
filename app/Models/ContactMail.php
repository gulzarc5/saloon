<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMail extends Model
{
    protected $fillable=[
        'category_id',
        'sub_category_id',
        'third_category_id',
        'name',
        'mobile',
        'message',
        'booking_date'
    ];

    
    public function category()
    {
        return $this->belongsTo('App\Models\JobCategory','category_id','id');
    }

    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory','sub_category_id','id');
    }

    public function thirdCategory()
    {
        return $this->belongsTo('App\Models\ThirdLevelCategory','third_category_id','id');
    }
}
