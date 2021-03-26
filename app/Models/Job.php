<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Job extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id','job_category','sub_category','last_category','description','main_image','mrp','price','status','is_deal','expire_date','discount'
    ];

    public function jobCategory()
    {
        return $this->belongsTo('App\Models\JobCategory','job_category','id');
    }
    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory','sub_category','id');
    }
    public function lastCategory()
    {
        return $this->belongsTo('App\Models\ThirdLevelCategory','last_category','id');
    }


    public function clientData()
    {
        return $this->belongsTo('App\Models\Client','user_id','id');
    }

    public function jobPricing()
    {
        return $this->hasMany('App\Models\JobPricing');
    }

    public function minPrice(){
        return $this->hasMany('App\Models\JobPricing', 'job_id', 'id')
        ->where('job_pricing.price', $this->jobPricing->min('price'));
    }
}
