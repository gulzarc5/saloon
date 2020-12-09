<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Job extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','job_category','description','main_image','mrp','price','status','is_man','is_woman','is_kids','man_mrp','man_price','woman_mrp','woman_price','kids_mrp','kids_price'
    ];

    public function jobCategory()
    {
        return $this->belongsTo('App\Models\JobCategory','job_category','id');
    }


    public function clientData()
    {
        return $this->belongsTo('App\Models\Client','user_id','id');
    }
}
