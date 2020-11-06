<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSchedule extends Model
{
    protected $table = 'job_schedules';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','job_id','date','status',
    ];
}
