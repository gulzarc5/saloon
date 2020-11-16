<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderJobs extends Model
{
    protected $table = 'order_job';
    protected $fillable = [
        'order_id','job_id',
    ];

    public function job()
    {
        return $this->belongsTo('App\Models\Job','job_id','id');
    }
}
