<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPricing extends Model
{
    protected $table = 'jobs_pricing';
    protected $fillable = [
        'job_id', 'cat_level_1', 'cat_level_2',	'cat_level_3', 'mrp', 'price'
    ];

    public function job()
    {
        return $this->belongsTo('App\Models\Job', 'job_id', 'id');
    }
}
