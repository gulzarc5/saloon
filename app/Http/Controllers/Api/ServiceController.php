<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobListResource;
use App\Http\Resources\JobDetailResource;
use App\Models\Job;
use App\Models\Review;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\ClientResource;
use App\Models\Client;

class ServiceController extends Controller
{
    public function serviceList(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'service_city' => 'required',
            'category_id' => 'required',
            'page' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required data Can not Be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $service_city = $request->input('service_city');
        $category_id = $request->input('category_id');
        $client_type = $request->input('client_type');
        $service_for = $request->input('service_for');
        $page = $request->input('page');

        $jobs = Job::select('jobs.*')->where('jobs.status',1)
        ->join('clients','clients.id','=','jobs.user_id');
        if (!empty($category_id)) {
            $jobs->where('jobs.job_category',$category_id);
        }
        if (!empty($service_city)) {
            $jobs->where('clients.service_city_id',$service_city);
        }
        if (!empty($client_type)) {
            $jobs->where('clients.clientType',$client_type);
        }
        if (!empty($service_for)) {
            if ($service_for == '1') {
                $jobs->where('jobs.is_man',2);
            }elseif ($service_for == '2') {
                $jobs->where('jobs.is_woman',2);
            }elseif ($service_for == '3') {
                $jobs->where('jobs.is_kids',2);
            }
        }
        $jobs->where('clients.status',1)
        ->where('clients.profile_status',2)
        ->where('clients.job_status',2)
        ->where('clients.verify_status',2)
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

    public function serviceSearch($search_key,$page)
    {
        $client = Client::where('status',1)->where('profile_status',2)->where('job_status',2)
        ->where('clients.verify_status',2);
            if (!empty($search_key)) {
                $client->where('clients.name','like', '%'.$search_key.'%');
            }
            $client->count();

            $client_query = clone $client;
            $total_client = $client->count('id');
            $total_page = intval(ceil($total_client / 12 ));
            $limit = ($page*12)-12;

            if ($total_client == 0) {
                $response = [
                    'status' => false,
                    'message' => 'Sorry No Data Found',
                    'search_key' => $search_key,
                    'data' => [],
                ];
                return response()->json($response, 200);
            }

            $client_data = $client->skip($limit)->take(12)->get();
            $response = [
                'status' => true,
                'message' => 'Data List',
                'tatal_page' => $total_page,
                'current_page' => $page,
                'total_item' => $total_client,
                'search_key' => $search_key,
                'data' => ClientResource::collection($client_data),
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

    public function insertReview(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'client_id' => 'required',
            'customer_id' => 'required',
            'comment' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required data Can not Be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }

        $review = new Review();   
        $review->customer_id = $request->input('customer_id');
        $review->client_id = $request->input('client_id');
        $review->comment = $request->input('comment');
        $review->rating = $request->input('rating');
        $review->save();

        $response = [
            'status' => true,
            'message' => 'Thank You For The Review',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }
}
