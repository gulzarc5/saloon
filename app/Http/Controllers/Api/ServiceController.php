<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobListResource;
use App\Http\Resources\JobDetailResource;
use App\Models\Job;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function serviceList($service_city,$category_id,$page,$client_type = null)
    {
        $jobs = Job::select('jobs.*')->where('jobs.status',1)
        ->join('clients','clients.id','=','jobs.user_id');
        if (!empty($jobs)) {
            $jobs->where('jobs.job_category',$category_id);
        }
        if (!empty($service_city)) {
            $jobs->where('clients.service_city_id',$service_city);
        }
        if (!empty($client_type)) {
            $jobs->where('clients.clientType',$client_type);
        }
        $jobs->where('clients.status',1)
        ->where('clients.profile_status',2)
        ->where('clients.job_status',2)
        ->count();

        $jobs_query = clone $jobs;
        $total_job = $jobs->count('jobs.id');
        $total_page = intval(ceil($total_job / 12 ));
        $limit = ($page*12)-12;

        if ($total_job == 0) {
            $response = [
                'status' => false,
                'message' => 'Sorry No Job Found',
                'data' => [],
            ];
            return response()->json($response, 200);
        }

        $job_data = $jobs->skip($limit)->take(12)->get();
        $response = [
            'status' => true,
            'message' => 'Service List',
            'tatal_page' => $total_page,
            'current_page' => $page,
            'total_item' => $total_job,
            'data' => JobListResource::collection($job_data),
        ];
        return response()->json($response, 200);

    }

    public function serviceDetails($service_id){
        $jobs = Job::find($service_id);
        $response = [
            'status' => true,
            'message' => 'Service Details',
            'data' => new JobDetailResource($jobs),
        ];
        return response()->json($response, 200);
    }
}
