<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $table = 'wish_list';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'vandor_id'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id',$this->primaryKey);
    }
}
