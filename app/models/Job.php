<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Job extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','job_category','description','main_image','mrp','price','status','is_man','is_woman','is_kids'
    ];

    public function jobCategory()
    {
        return $this->belongsTo('App\Models\JobCategory','job_category','id');
    }

    public function jobSchedule()
    {
        // $date = Carbon::now()->setTimezone('Asia/Kolkata')->toDateString();
        return $this->hasMany('App\Models\JobSchedule','job_id','id')->whereDate('created_at', '>=', Carbon::now());
    }
}
