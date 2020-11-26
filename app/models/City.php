<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';
    protected $primary_key = 'id';

    protected $fillable = [
        'state_id','name','status'
    ];

    public function state()
    {
        return $this->belongsTo('App\Models\State','state_id',$this->primaryKey);
    }

}
