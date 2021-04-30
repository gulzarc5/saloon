<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppSetting\DealResource;
use App\Http\Resources\AppSetting\TopClientResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\Combo\AppLoadComboListResource;
use App\Models\Client;
use App\Models\Job;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use App\Models\ServiceCity;
use App\Models\Slider;
use App\Models\SubCategory;
use App\Models\ThirdLevelCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        if (!empty($request->get('latitude'))) {
            $latitude = $request->get('latitude');
            $longitude =  $request->get('longitude');
        }
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
       
       //    $sqlDistance = DB::raw('
       //         ST_Distance_Sphere(
       //             point(clients.longitude , clients.latitude),
       //             point('.$longitude.', '.$latitude.')
       //         ) /1000
       //     ');
        $top_saloon =  Client::where('profile_status', 2)
            ->where('job_status', 2)->where('status', 1)
            ->where('clientType', 2)->select('clients.*')
            ->selectRaw("{$sqlDistance} AS distance")
            ->withCount(['review as average_rating' => function ($query) {
                $query->select(DB::raw('coalesce(avg(rating),0)'));
            }])
            ->orderBy('distance')->limit(10)->get();

        $top_free_launcer =  Client::where('profile_status', 2)
            ->where('job_status', 2)->where('status', 1)
            ->where('clientType', 1)->select('clients.*')
            ->selectRaw("{$sqlDistance} AS distance")
            ->withCount(['review as average_rating' => function ($query) {
                $query->select(DB::raw('coalesce(avg(rating),0)'));
            }])
            ->orderBy('distance')->limit(10)->get();       
        

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
            ->orderBy('distance')->orderBy('max_discount', 'desc')->distinct('clients.id')->limit(10)->get();  
        

        $combo_services = Job::where('jobs.product_type',2)
        ->where('jobs.status',1)
        ->select(
            'clients.name as client_name',
            'clients.mobile as client_mobile',
            'clients.state as client_state',
            'clients.address as client_address',
            'clients.pin as client_pin',
            'clients.work_experience as client_work_experience',
            'clients.image as client_image',
            'jobs.*'
            )
        ->rightJoin('clients','jobs.user_id','clients.id')
        ->where('clients.job_status', 2)->where('clients.status', 1)
        ->where('clients.profile_status', 2)
        ->selectRaw("{$sqlDistance} AS distance")
        ->orderBy('distance')->distinct('job.id')->limit(10)->get();  
  

        $response = [
            'status' => true,
            'message' => 'Service City List',
            'data' => [
                'sliders' => $Sliders,
                'category' => CategoryResource::collection($category_list),
                'top_saloon' => TopClientResource::collection($top_saloon),
                'top_free_launcer' => TopClientResource::collection($top_free_launcer),
                'deal_of_the_day' => DealResource::collection($deal_of_the_day),
                'combo_products' => AppLoadComboListResource::collection($combo_services),
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
