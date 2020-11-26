<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquery extends Model
{
    protected $table = 'review';
    protected $primary_key = 'id';

    protected $fillable = [
        'customer_id','client_id','comment','rating'
    ];
}
