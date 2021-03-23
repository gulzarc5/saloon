<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Client;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use App\Models\ServiceCity;
use App\Models\Slider;
use App\Http\Resources\ClientResource;
use App\Models\SubCategory;
use App\Models\ThirdLevelCategory;
use Illuminate\Support\Facades\DB;
use validator;
class AppSettingController extends Controller
{
    public function serviceCity()
    {
        $serviceCity = ServiceCity::select('id', 'name')->where('status', 1)->get();
        $response = [
            'status' => true,
            'message' => 'Service City List',
            'data' => $serviceCity,
        ];
        return response()->json($response, 200);
    }

    public function AppLoadApi(Request $request)
    {
        $latitude  =   "28.418715";
        $longitude =   "77.0478997";

        $latitude = $request->get('latitude');
        $longitude =  $request->get('longitude');
        $Sliders = Slider::get();
        $category_list = JobCategory::where('status', 1)->get();
        // $top_saloon = Client::where('profile_status', 2)->where('job_status', 2)->where('status', 1)->where('clientType', 2)->withCount(['review as average_rating' => function ($query) {
        //     $query->select(DB::raw('coalesce(avg(rating),0)'));
        // }]);
        $sqlDistance = DB::raw('( 111.045 * acos( cos( radians(' . $latitude . ') ) 
       * cos( radians( clients.latitude ) ) 
       * cos( radians( clients.longitude ) 
       - radians(' . $longitude  . ') ) 
       + sin( radians(' . $latitude  . ') ) 
       * sin( radians( clients.latitude ) ) ) )');
        $top_saloon =  Client::where('profile_status', 2)->where('job_status', 2)->where('status', 1)->where('clientType', 2)->select('clients.*')->selectRaw("{$sqlDistance} AS distance")->withCount(['review as average_rating' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rating),0)'));
        }])->orderBy('distance')->limit(10)->get();

        $top_free_launcer =  Client::where('profile_status', 2)->where('job_status', 2)->where('status', 1)->where('clientType', 1)->select('clients.*')->selectRaw("{$sqlDistance} AS distance")->withCount(['review as average_rating' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rating),0)'));
        }])->orderBy('distance')->limit(10)->get();       
        

        // $top_free_launcer = Client::where('profile_status', 2)->where('job_status', 2)->where('status', 1)->where('clientType', 1)->withCount(['review as average_rating' => function ($query) {
        //     $query->select(DB::raw('coalesce(avg(rating),0)'));
        // }])->orderByDesc('average_rating')->limit(10)->get();
        $response = [
            'status' => true,
            'message' => 'Service City List',
            'data' => [
                'sliders' => $Sliders,
                'category' => CategoryResource::collection($category_list),
                'top_saloon' => ClientResource::collection($top_saloon),
                'top_free_launcer' => ClientResource::collection($top_free_launcer),
            ],
        ];
        return response()->json($response, 200);
    }

    public function serviceList()
    {
        $category_list = JobCategory::where('status', 1)->withCount('subCategoryWithStatus as sub_category_count')->get();
        $response = [
            'status' => true,
            'message' => 'Service Category List',
            'data' => $category_list,
        ];
        return response()->json($response, 200);
    }
    public function subCategoryList($main_category)
    {
        $category_list = SubCategory::where('category_id', $main_category)->withCount('thirdCategoryWithStatus as third_category_count')->where('status', 1)->get();
        $response = [
            'status' => true,
            'message' => 'Sub Category List',
            'data' => $category_list,
        ];
        return response()->json($response, 200);
    }
    public function thirdCategoryList($sub_category)
    {
        $category_list = ThirdLevelCategory::where('sub_category_id', $sub_category)->where('status', 1)->get();
        $response = [
            'status' => true,
            'message' => 'Sub Category List',
            'data' => $category_list,
        ];
        return response()->json($response, 200);
    }
}
