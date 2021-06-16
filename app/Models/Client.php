<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class Client extends Authenticatable
{
    protected $table ='clients';
    protected $fillable = [
        'name','mobile','email','otp','work_experience','state','city','address','pin','image','gst','latitude','longitude','opening_time','closing_time','clientType','status','password','profile_status','api_token','service_city_id','job_status','address_proof','address_proof_file','photo_proof','photo_proof_file','business_proof','business_proof_file','verify_status','firsbase_token','max_discount'
    ];
    protected $hidden = ['password'];


    public function images()
    {
        return $this->hasMany('App\Models\ClientImage','client_id','id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job','user_id','id')->where('product_type',1);
    }

    public function comboJobs()
    {
        return $this->hasMany('App\Models\Job','user_id','id')->where('product_type',2);
    }

    public function jobWithDeal()
    {
        return $this->hasMany('App\Models\Job','user_id','id')->where('is_deal','Y')->where('expire_date','>=',Carbon::today()->toDateString());
    }

    public function serviceCity()
    {
        return $this->belongsTo('App\Models\ServiceCity','service_city_id','id');
    }

    public function clientSchedules()
    {
        return $this->hasMany('App\Models\ClientSchedule','user_id','id')->whereDate('date','>=' ,Carbon::now());
    }

    public function review()
    {
        return $this->hasMany('App\Models\Review','client_id','id');
    }



    public function scopeNearbyLat($query,$lat,$lng,$radius)
    {
        $subselect = clone $query;

        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' . $lat . ') ) 
       * cos( radians( clients.latitude ) ) 
       * cos( radians( clients.longitude ) 
       - radians(' . $lng  . ') ) 
       + sin( radians(' . $lat  . ') ) 
       * sin( radians( clients.latitude ) ) ) ) as distance');

        $subselect->select('*',DB::raw($sqlDistance));

        $query
            ->from(DB::raw('(' . $subselect->toSql() . ') AS d'))
            ->where('distance', '<=', $radius);
    }
}
