<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCity extends Model
{
    protected $table = 'service_cities';
    protected $primary_key = 'id';

    protected $fillable = [
        'name','status'
    ];
}
