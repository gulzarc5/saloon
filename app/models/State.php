<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'state';
    protected $primary_key = 'id';

    protected $fillable = [
        'name','status'
    ];
}
