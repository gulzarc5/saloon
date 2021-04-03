<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Client\Deal\DealAddRequest;
use App\Http\Resources\AppSetting\DealResource;
use App\Http\Resources\ClientJobResource;
use App\Models\Client;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientDealController extends Controller
{
    public function add(DealAddRequest $request,$service_id)
    {
        $job = Job::find($service_id);
        if ($job) {
            $this->dealSave($job,$request);
            $this->clientDiscountUpdate($job->user_id);
        }
        
        $response = [
            'status' => true,
            'message' => 'Deal Added Successfully',
        ];
        return response()->json($response, 200);
    }

    public function remove($service_id)
    {
        $job = Job::find($service_id);
        $job->is_deal = 'N';
        $job->save();
        $this->clientDiscountUpdate($job->user_id);
        $response = [
            'status' => true,
            'message' => 'Deal Removed Successfully',
        ];
        return response()->json($response, 200);
    }

    public function list($client_id)
    {
        $service = Job::where('user_id', $client_id)
        ->where('is_deal','Y')->where('expire_date','>=',Carbon::now()
        ->toDateString())->orderBy('discount','desc')->get();
        
        $response = [
            'status' => true,
            'message' => 'Service List',
            'data' => ClientJobResource::collection($service),
        ];
        return response()->json($response, 200);
    }

    private function dealSave(Job $job,$request){
        $job->is_deal = 'Y';
        $job->expire_date = $request->input('expire_date');
        $job->discount = $request->input('discount');
        $job->save();
        return true;
    }

    private function clientDiscountUpdate($client_id)
    {
        $max_discounted =  Job::where('user_id',$client_id)
        ->where('is_deal','Y')
        ->where('expire_date','>=',Carbon::now()->toDateString())
        ->max('discount');
    
        $client = Client::find($client_id);
        if ($client) {
            $client->max_discount = $max_discounted ?? 0;
            $client->save();
        }
        return true;
    }

    public function dealsViewAll(Request $request)
    {
        $latitude  =   "28.418715";
        $longitude =   "77.0478997";
        if (!empty($request->input('latitude')) && $request->get('longitude')) {
            $latitude = $request->get('latitude');
            $longitude =  $request->get('longitude');
        }

        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' . $latitude . ') ) 
        * cos( radians( clients.latitude ) ) 
        * cos( radians( clients.longitude ) 
        - radians(' . $longitude  . ') ) 
        + sin( radians(' . $latitude  . ') ) 
        * sin( radians( clients.latitude ) ) ) )');

        $deal_of_the_day = Client::where('clients.profile_status', 2)
        ->where('clients.job_status', 2)->where('clients.status', 1)
        ->select('clients.*')
        ->selectRaw("{$sqlDistance} AS distance")
        ->withCount(['review as average_rating' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rating),0)'));
        }])
        ->leftJoin('jobs','jobs.user_id','clients.id')
        ->where('jobs.is_deal','Y')
        ->where('jobs.status',1)
        ->where('jobs.expire_date','>=',Carbon::today()->toDateString())
        ->orderBy('distance')->orderBy('max_discount', 'desc')->distinct('clients.id')->paginate(12);;  

        $response = [
            'status' => true,
            'message' => 'Service List',
            'total_page' => $deal_of_the_day->lastPage(),
            'current_page' =>$deal_of_the_day->currentPage(),
            'total_data' =>$deal_of_the_day->total(),
            'has_more_page' =>$deal_of_the_day->hasMorePages(),
            'data' => DealResource::collection($deal_of_the_day),
        ];
        return response()->json($response, 200);
    }
}
