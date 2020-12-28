<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use App\Models\ServiceCity;
use App\Models\Slider;
use DB;
use App\Http\Resources\ClientResource;

class AppSettingController extends Controller
{
    public function serviceCity()
    {
        $serviceCity = ServiceCity::select('id','name')->where('status',1)->get();
        $response = [
            'status' => true,
            'message' => 'Service City List',
            'data' => $serviceCity,
        ];
        return response()->json($response, 200);
    }

    public function AppLoadApi()
    {
        $Sliders = Slider::get();
        $category_list = JobCategory::where('status',1)->get();
        $top_saloon = Client::where('profile_status',2)->where('job_status',2)->where('status',1)->where('clientType',2)->withCount(['review as average_rating' => function($query) {
            $query->select(DB::raw('coalesce(avg(rating),0)'));
        }])->orderByDesc('average_rating')->limit(9)->get();
        $top_free_launcer = Client::where('profile_status',2)->where('job_status',1)->where('status',1)->where('clientType',2)->withCount(['review as average_rating' => function($query) {
            $query->select(DB::raw('coalesce(avg(rating),0)'));
        }])->orderByDesc('average_rating')->limit(10)->get();
        $response = [
            'status' => true,
            'message' => 'Service City List',
            'data' => [
                'sliders' => $Sliders,
                'category' => $category_list,
                'top_saloon' => ClientResource::collection($top_saloon),
                'top_free_launcer' => ClientResource::collection($top_free_launcer),
            ],
        ];
        return response()->json($response, 200);
    }

    public function serviceList()
    {
        $category_list = JobCategory::where('status',1)->get();
        $response = [
            'status' => true,
            'message' => 'Service Category List',
            'data' => $category_list,
        ];
        return response()->json($response, 200);
    }
}
