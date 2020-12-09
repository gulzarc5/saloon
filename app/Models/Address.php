<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $primary_key = 'id';

    protected $fillable = [
        'user_id','name','mobile','email','state','city','pin','address','latitude','longitude'
    ];
}
