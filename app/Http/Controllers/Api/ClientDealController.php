<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

use App\Http\Requests\Api\Client\Deal\DealAddRequest;
use App\Http\Resources\ClientJobResource;
use App\Models\Client;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
