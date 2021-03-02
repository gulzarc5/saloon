<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = [
        'category_id',
        'name',
        'image',
        'man',
        'woman',
        'status'
    ];

    public function serviceCategory()
    {
        return $this->belongsTo('App\Models\JobCategory', 'category_id', 'id');
    }

    
}
