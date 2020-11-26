<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use App\Models\ServiceCity;
use App\Models\Slider;

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
        $response = [
            'status' => true,
            'message' => 'Service City List',
            'data' => [
                'sliders' => $Sliders,
                'category' => $category_list,
                'top_saloon' => [],
                'top_free_launcer' => [],
            ],
        ];
        return response()->json($response, 200);
    }
}
