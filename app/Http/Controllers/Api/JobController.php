<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientJobResource;
use App\Models\Client;
use App\Models\Job;
use App\Models\JobPricing;
use App\Models\JobSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function clientServiceAdd(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'client_id' => 'required|numeric',
            'main_category' => 'required|array',
            'mrp' => 'array|min:1',
            'price' => 'array|min:1',
            'main_category.*' => 'required',
            'mrp.*' => 'required',
            'price.*' => 'required',
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

        $client_id = $request->input('client_id');
        $description = $request->input('description');
        $main_category = $request->input('main_category');
        $sub_category = $request->input('sub_category');
        $last_category = $request->input('last_category');
        $mrp = $request->input('mrp');
        $price = $request->input('price');

        if ($request->has('main_category')) {            
            for ($i = 0; $i < count($main_category); $i++) {
                $check = Job::where('user_id',$client_id)->where('job_category',$main_category[$i]);
                if (isset( $sub_category[$i] ) && !empty( $sub_category[$i] )) {
                    $check->where('sub_category',$sub_category[$i]);
                }
                if (isset( $sub_category[$i] ) && !empty( $sub_category[$i] )) {
                    $check->where('sub_category',$sub_category[$i]);
                }
                if ($check->count() == 0) {
                    # code...
                    $job = new Job();
                    $job->user_id = $client_id;
                    $job->description = $description;
                    $job->job_category = $main_category[$i] ?? null;
                    $job->sub_category = $sub_category[$i] ?? null;
                    $job->last_category = $last_category[$i] ?? null;
                    $job->mrp = $mrp[$i] ?? null;
                    $job->price = $price[$i] ?? null;
                    $job->save();
                }
            }
            $client = Client::find($client_id);
            $client->job_status = 2;
            $client->save();

            $response = [
                'status' => true,
                'message' => 'Service Added Successfully',
                'error_code' => false,
                'error_message' => $validator->errors(),

            ];
            return response()->json($response, 200);
        }
    }

    public function clientServiceList($client_id)
    {
        $service = Job::where('user_id', $client_id)->get();
        $response = [
            'status' => true,
            'message' => 'Service List',
            'data' => ClientJobResource::collection($service),
        ];
        return response()->json($response, 200);
    }
    public function clientServiceEdit($service_list_id)
    {
        $service = Job::find($service_list_id);
        $response = [
            'status' => true,
            'message' => 'Service List',
            'data' => new ClientJobResource($service),
        ];
        return response()->json($response, 200);
    }

    public function clientServiceUpdate(Request $request, $service_list_id)
    {
        $validator =  Validator::make($request->all(), [
            'mrp' => 'required',
            'price' => 'required',
            'main_category_id' => 'required',
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
        $mrp = $request->input('mrp');
        $price = $request->input('price');

        $main_category_id    = $request->input('main_category_id');
        $sub_category_id    = $request->input('sub_category_id');
        $last_category_id = $request->input('last_category_id');
        $description = $request->input('description');

        $service = Job::find($service_list_id);
        if ($service) {
            $service->job_category = $main_category_id;
            $service->sub_category = $sub_category_id;
            $service->last_category = $last_category_id;
            $service->mrp = $mrp;
            $service->price = $price;
            $service->description = $description;
            $service->save();
        }
       
        $response = [
            'status' => true,
            'message' => 'Service Updated successfully',
            'error_code' => false,
            'error_message' => null,

        ];
        return response()->json($response, 200);
    }

    public function clientServiceStatusUpdate($service_id, $status)
    {
        $job = Job::find($service_id);
        $job->status = $status;
        $job->save();
        $response = [
            'status' => true,
            'message' => 'Service Status updated successfully',
        ];
        return response()->json($response, 200);
    }
}
