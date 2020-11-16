<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquery extends Model
{
    protected $table = 'enquery';
    protected $primary_key = 'id';

    protected $fillable = [
        'type','name','mobile','subject','message'
    ];
}
