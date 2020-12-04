<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Order;
use App\Models\OrderJobs;
use Illuminate\Http\Request;
use Validator;

use Razorpay\Api\Api;

class OrderController extends Controller
{
    public function orderPlace(Request $request)
    {
        // service type 1 = man, 2 = woman, 3= kids
        $validator =  Validator::make($request->all(),[
            'user_id' =>'required',
            'service_id' =>'required|array|min:1',
            'service_id.*' =>'required',
            'service_type' =>'required|array|min:1',
            'service_type.*' =>'required',
            'service_time' =>'required|date_format:Y-m-d H:i:s',
            'address_id'=>'required',
            'vendor_id' => 'required'
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
        $service_id = $request->input('service_id');
        $service_type = $request->input('service_type');
        $vendor_id = $request->input('vendor_id');
        //check service is available or not
        $vendor_id = null;
        for ($i=0; $i < count($service_id); $i++) { 
            if (isset($service_id[$i]) && !empty($service_id[$i]) && isset($service_type[$i]) && !empty($service_type[$i])) {
                $checkService = Job::where('status',1);
                if($service_id[$i] = 1){
                    $checkService->where('is_man',2);
                }elseif ($service_id[$i] = 2) {
                    $checkService->where('is_woman',2);
                }else {
                    $checkService->where('is_kids',2);
                }
                if ($checkService->count() == 0) {
                    $response = [
                        'status' => false,
                        'message' => 'Service Not Available',
                        'error_code' => false,
                        'error_message' => null,
                    ];
                    return response()->json($response, 200);
                }
            }
        }

        //check Vendor is available or not in scheduled date
        // $checkSchedule = 


        foreach ($service_id as $key => $item) {
            $job_count = Job::select('jobs.id','jobs.user_id')->where('jobs.status',1)
            ->join('clients','clients.id','=','jobs.user_id')
            ->where('clients.clientType',1);
            $vendor_data = $job_count->first();
            $vendor_id = $vendor_data->user_id;
            if ($job_count->count() > 0) {
                $validator =  Validator::make($request->all(),[
                    'address_id' =>'required',
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
            }
        }

        $total_amount = 0;
        //Order Creation
        $order = new Order();
        $order->customer_id = $request->input('user_id');
        $order->customer_address_id = $request->input('address_id');
        $order->service_time = $request->input('service_time');
        $order->vendor_id = $vendor_id;
        if ($order->save()) {
            foreach ($service_id as $key => $service) {
                $order_job = new OrderJobs();
                $order_job->order_id = $order->id;
                $order_job->job_id = $service;
                $order_job->save();
                $amount_fetch = Job::find($service);
                $total_amount+=$amount_fetch->price;
            }
            $order->amount = $total_amount;

            $api = new Api(config('services.razorpay.id'), config('services.razorpay.key'));
                $orders = $api->order->create(array(
                    'receipt' =>$order->id,
                    'amount' => $total_amount*100,
                    'currency' => 'INR',
                    )
                );

                $order->payment_request_id = $orders['id'];
                $order->save();

                $payment_data = [
                    'key_id' => config('services.razorpay.id'),
                    'amount' => $total_amount*100,
                    'order_id' => $orders['id'],
                    'name' => $order->customer->name,
                    'email' => $order->customer->email,
                    'mobile' => $order->customer->mobile,
                ];

                $response = [
                    'status' => true,
                    'message' => 'Order Place',
                    'error_code' => false,
                    'error_message' => null,
                    'data' => [
                        'order_id' => $order->id,
                        'payment_status' => 1,
                        'amount' => $total_amount,
                        'payment_data' => $payment_data,
                    ],
                ];

               return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }


    public function paymentVerify(Request $request){
        $validator =  Validator::make($request->all(),[
            'user_id' => 'required',
            'razorpay_order_id' => 'required', // 1 = normal, 2 = Express
            'razorpay_payment_id' => 'required', // 1 = cod, 2 = online
            'razorpay_signature' => 'required',
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
                'data' => [],
            ];
            return response()->json($response, 200);
        }

        $verify = $this->signatureVerify(
            $request->input('razorpay_order_id'),
            $request->input('razorpay_payment_id'),
            $request->input('razorpay_signature')
        );
        if ($verify) {
            $order = Order::find($request->input('order_id'));
            $order->payment_id =  $request->input('razorpay_payment_id');
            $order->payment_status = 2;
            $order->save();
            $response = [
                'status' => true,
                'message' => 'Payment Success',
            ];
            return response()->json($response, 200);
        }else{
            $response = [
                'status' => false,
                'message' => 'Payment Failed',
            ];
            return response()->json($response, 200);
        }
    }

    private function signatureVerify($order_id,$payment_id,$signature)
    {
        try {
            $api = new Api(config('services.razorpay.id'), config('services.razorpay.key'));
            $attributes = array(
                'razorpay_order_id' => $order_id,
                'razorpay_payment_id' => $payment_id,
                'razorpay_signature' => $signature
            );

            $api->utility->verifyPaymentSignature($attributes);
            $success = true;
        } catch (\Exception $e) {
            $success = false;
        }
        return $success;
    }
}
