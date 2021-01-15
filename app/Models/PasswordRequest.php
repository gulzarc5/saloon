<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordRequest extends Model
{
    protected $table = 'password_request';
    protected $fillable = [
        'user_id','status','user_type'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','user_id','id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client','user_id','id');
    }
}
