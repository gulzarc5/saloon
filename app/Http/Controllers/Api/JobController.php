<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientJobResource;
use App\Models\Client;
use App\Models\Job;
use App\Models\JobSchedule;
use Illuminate\Http\Request;
use  Validator;

class JobController extends Controller
{
    public function clientServiceAdd(Request $request,$client_id)
    {
        $validator =  Validator::make($request->all(),[
            'job_id.*' => 'required|numeric',
            'is_man.*' => 'required|in:1,2',
            'man_mrp.*' => 'required',
            'man_price.*' => 'required',
            'is_women.*' => 'required|in:1,2',
            'woman_mrp.*' => 'required',
            'woman_price.*' => 'required',
            'is_kids.*' => 'required|in:1,2',
            'kids_mrp.*' => 'required',
            'kids_price.*' => 'required',
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

        $job_id = $request->input('job_id');
        $man_mrp    = $request->input('man_mrp');
        $man_price = $request->input('man_price');
        $is_man = $request->input('is_man');

        $woman_mrp    = $request->input('woman_mrp');
        $woman_price = $request->input('woman_price');
        $is_woman = $request->input('is_women');

        $kids_mrp    = $request->input('kids_mrp');
        $kids_price = $request->input('kids_price');
        $is_kids = $request->input('is_kids');
        $description = $request->input('description');

        $length = count($job_id);
   
        for ($i = 0; $i < $length; $i++) {
            $check_job = Job::where('job_category',$job_id[$i])->where('user_id',$client_id)->count();

            if ($check_job == 0) {
                $job = new Job();
                $job->user_id = $client_id;
                $job->job_category = $job_id[$i];
                $job->is_man = $is_man[$i];
                $job->man_mrp = $man_mrp[$i];
                $job->man_price = $man_price[$i];
                $job->woman_mrp = $woman_mrp[$i];
                $job->is_woman = $is_woman[$i];
                $job->woman_price = $woman_price[$i];
                $job->is_kids = $is_kids[$i];
                $job->kids_mrp = $kids_mrp[$i];
                $job->kids_price = $kids_price[$i];
                $job->description = $description[$i];
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
        $validator =  Validator::make($request->all(),[
            'is_man' => 'required|in:1,2',
            'man_mrp' => 'required',
            'man_price' => 'required',
            'is_women' => 'required|in:1,2',
            'woman_mrp' => 'required',
            'woman_price' => 'required',
            'is_kids' => 'required|in:1,2',
            'kids_mrp' => 'required',
            'kids_price' => 'required',
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
        $man_mrp    = $request->input('man_mrp');
        $man_price = $request->input('man_price');
        $is_man = $request->input('is_man');

        $woman_mrp    = $request->input('woman_mrp');
        $woman_price = $request->input('woman_price');
        $is_woman = $request->input('is_women');

        $kids_mrp    = $request->input('kids_mrp');
        $kids_price = $request->input('kids_price');
        $is_kids = $request->input('is_kids');
        $description = $request->input('description');
        
        $service = Job::find($service_list_id);
        $service->is_man = $is_man;
        $service->man_mrp = $man_mrp;
        $service->man_price = $man_price;
        $service->woman_mrp = $woman_mrp;
        $service->is_woman = $is_woman;
        $service->woman_price = $woman_price;
        $service->is_kids = $is_kids;
        $service->kids_mrp = $kids_mrp;
        $service->kids_price = $kids_price;
        $service->description = $description;
        $service->save();
        $response = [
            'status' => true,
            'message' => 'Service Updated successfully',
            'error_code' => false,
            'error_message' => null,

        ];
        return response()->json($response, 200);
    }

    public function clientServiceStatusUpdate($service_id,$status)
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
