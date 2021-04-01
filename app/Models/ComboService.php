<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboService extends Model
{
    protected $table = 'combo_jobs';
    protected $fillable = [
        'job_id','name','price','mrp'
    ];
}
