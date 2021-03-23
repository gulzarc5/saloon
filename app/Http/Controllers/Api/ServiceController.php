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
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function serviceList(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'service_city' => 'required',
            'category_id' => 'required',
            'type' => 'required|in:1,2,3', //1 = main Category, 2 = Sub Category, 3 = third Category
            'page' => 'required|in:1,2',
            'latitude' => 'required',
            'longitude' => 'required',
            'client_type' => 'required:in:1,2', // 1 = freelauncer , 2 = Salon
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $service_city = $request->input('service_city');
        $category_id = $request->input('category_id');
        $type = $request->input('type');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $client_type = $request->input('client_type');
        // OPTIONAL FIELDS //
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');
        $ac = $request->input('ac'); // 2 = yes
        $parking = $request->input('parking'); // 2 = yes
        $wifi = $request->input('wifi'); // 2 = yes
        $music = $request->input('music'); // 2 = yes
        $page = $request->input('page');
        $sort_by = $request->input('sort_by'); // 1 = distance low to high, 2 = distance high to low, 3 = price low to high, 4 = price high to low

        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' . $latitude . ') ) 
       * cos( radians( clients.latitude ) ) 
       * cos( radians( clients.longitude ) 
       - radians(' . $longitude  . ') ) 
       + sin( radians(' . $latitude  . ') ) 
       * sin( radians( clients.latitude ) ) ) )');

        $jobs = Job::select('jobs.*')->where('jobs.status',1)
        ->join('clients','clients.id','=','jobs.user_id')->selectRaw("{$sqlDistance} AS distance");
        if ($type == '1') {
            $jobs->where('jobs.job_category',$category_id);
        }elseif ($type == '2') {
            $jobs->where('jobs.sub_category',$category_id);
        }else{
            $jobs->where('jobs.last_category',$category_id);
        }
        if (!empty($service_city)) {
            $jobs->where('clients.service_city_id',$service_city);
        }
        if (!empty($client_type)) {
            $jobs->where('clients.clientType',$client_type);
        }
        if (!empty($price_from) && !empty($price_to)) {
            $jobs->whereBetween('jobs.price', [$price_from, $price_to]);
        }
        if (!empty($ac) && $ac == '2') {
            $jobs->where('clients.ac', 2);
        }
        if (!empty($parking) && $parking == '2') {
            $jobs->where('clients.parking', 2);
        }
        if (!empty($wifi) && $wifi == '2') {
            $jobs->where('clients.wifi', 2);
        }
        if (!empty($music) && $music == '2') {
            $jobs->where('clients.music', 2);
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
        if (!empty($sort_by)) {
            if ($sort_by == '1') {
                $jobs_query->orderBy('distance','asc');
            } else  if ($sort_by == '2'){
                $jobs_query->orderBy('distance','desc');
            }else  if ($sort_by == '3'){
                $jobs_query->orderBy('price','asc');
            }else  if ($sort_by == '4'){
                $jobs_query->orderBy('price','desc');
            }
            
        }else{
            $jobs_query->orderBy('distance','asc');
        }

        $job_data = $jobs_query->skip($limit)->take(12)->get();
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

    public function serviceSearch(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'search_key' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $search_key = $request->input('search_key');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' . $latitude . ') ) 
       * cos( radians( clients.latitude ) ) 
       * cos( radians( clients.longitude ) 
       - radians(' . $longitude  . ') ) 
       + sin( radians(' . $latitude  . ') ) 
       * sin( radians( clients.latitude ) ) ) )');

        $client = Client::select('clients.*')->where('clients.status',1)
        ->where('clients.profile_status',2)
        ->where('clients.job_status',2)
        ->where('clients.verify_status',2)
        ->selectRaw("{$sqlDistance} AS distance");
        if (!empty($search_key)) {
            $client->where('clients.name','like', '%'.$search_key.'%');
        }
        $client->orderBy('distance','asc');
        $client = $client->paginate(12);

        $response = [
            'status' => true,
            'message' => 'Data List',
            'total_page' => $client->lastPage(),
            'current_page' =>$client->currentPage(),
            'total_data' =>$client->total(),
            'has_more_page' =>$client->hasMorePages(),
            'search_key' => $search_key,
            'data' => ClientResource::collection($client),
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
