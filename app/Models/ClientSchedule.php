<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSchedule extends Model
{
    protected $table = 'client_schedules';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','date','status',
    ];
}
