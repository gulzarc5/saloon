<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
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
            'mrp.*' => 'required',
            'price.*' => 'required',
            'is_man.*' => 'required|in:1,2',
            'is_women.*' => 'required|in:1,2',
            'is_kids.*' => 'required|in:1,2',
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
        $mrp    = $request->input('mrp');
        $price = $request->input('price');
        $is_man = $request->input('is_man');
        $is_woman = $request->input('is_women');
        $is_kids = $request->input('is_kids');
        $description = $request->input('description');

        $length = count($job_id);
        for ($i = 0; $i < $length; $i++) {
            $check_job = Job::where('job_category',$job_id[$i])->count();
            if ($check_job == 0) {
                $job = new Job();
                $job->user_id = $client_id;
                $job->job_category = $job_id[$i];
                $job->mrp = $mrp[$i];
                $job->price = $price[$i];
                $job->is_man = $is_man[$i];
                $job->is_woman = $is_woman[$i];
                $job->is_kids = $is_kids[$i];
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

    public function clientServiceScheduleUpdate(Request $request, $job_id)
    {
        $validator =  Validator::make($request->all(),[
            'date' => ['required','date_format:Y-m-d'],
            'status' => 'required|in:1,2',
            'client_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $date = $request->input('date');
        $client_id = $request->input('client_id');
        $status = $request->input('status');
        if ($status == '1') {
            $job = JobSchedule::firstOrNew(['id'=>$job_id,'date'=>$date,'user_id'=>$client_id]);
            if ($job) {
                $job->job_id = $job_id;
                $job->user_id = $client_id;
                $job->date = $date;
                $job->status = $status;
                $job->save();
            }
        } else {
            $job = JobSchedule::firstOrNew(['id'=>$job_id,'date'=>$date,'user_id'=>$client_id]);
            if ($job) {
                $job->delete();
            }
        }
        $response = [
            'status' => true,
            'message' => 'Job Scheduled Successfully',
            'error_code' => false,
            'error_message' => null,
        ];
        return response()->json($response, 200);
    }
}
