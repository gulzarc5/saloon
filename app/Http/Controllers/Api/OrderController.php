<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientSchedule;
use App\Models\Job;
use App\Models\Order;
use App\Models\OrderJobs;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\SmsHelper\PushHelperVendor;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;

class OrderController extends Controller
{
    public function orderPlace(Request $request)
    {
        // service type 1 = man, 2 = woman, 3= kids
        $validator =  Validator::make($request->all(), [
            'user_id' => 'required',
            'vendor_id' => 'required',
            'offer_id' => 'nullable|numeric',
            'offer_job_id' => 'nullable|numeric',
            'coupon_id' => 'nullable|numeric',
            'is_wallet' => 'nullable|numeric|in:1,2', // 1 = wallet use, 2 = No
            'job_id' => 'required|array|min:1',
            'job_id.*' => 'required',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required',
            'address_id' => 'nullable|numeric',
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
        $service_id = $request->input('service_id');
        $service_for = $request->input('service_for');
        $vendor_id = $request->input('vendor_id');
        $service_date = $request->input('service_time');
        //check service is available or not
        for ($i = 0; $i < count($service_id); $i++) {
            if (isset($service_id[$i]) && !empty($service_id[$i]) && isset($service_for[$i]) && !empty($service_for[$i])) {
                $checkService = Job::where('id', $service_id[$i])->where('status', 1);
                if ($service_for[$i] == '1') {
                    $checkService->where('is_man', 2);
                } elseif ($service_for[$i] == '2') {
                    $checkService->where('is_woman', 2);
                }
                elseif ($service_for[$i] == '3') {
                    $checkService->where('is_kids', 2);
                }
                if ($checkService->count() == 0) {
                    $response = [
                        'status' => false,
                        'message' => 'Service Not Found',
                        'error_code' => false,
                        'error_message' => null,
                    ];
                    return response()->json($response, 200);
                }
            }
        }

        //check Vendor is available or not in scheduled date
        // $checkSchedule = 

        $service_date_check = Carbon::parse($service_date)->toDateString();
        if (!empty($service_date_check)) {
            $checkSchedule = ClientSchedule::where('user_id', $vendor_id)->where('date', $service_date_check)->count();
            if ($checkSchedule > 0) {
                $response = [
                    'status' => false,
                    'message' => 'Service Not Available At Scheduled Date',
                    'error_code' => false,
                    'error_message' => null,
                ];
                return response()->json($response, 200);
            }
        }

        ////////////// Validation End////////////////////


        $total_amount = 0;
        //Order Creation
        $order = new Order();
        $order->customer_id = $request->input('user_id');
        $order->customer_address_id = $request->input('address_id');
        $order->service_time = $request->input('service_time');
        $order->vendor_id = $vendor_id;
        if ($order->save()) {
            for ($i = 0; $i < count($service_id); $i++) {
                if (isset($service_id[$i]) && !empty($service_id[$i]) && isset($service_for[$i]) && !empty($service_for[$i])) {
                    $checkService = Job::where('id', $service_id[$i])->where('status', 1);
                    if ($service_for[$i] == '1') {
                        $checkService->where('is_man', 2);
                    } elseif ($service_for[$i] == '2') {
                        $checkService->where('is_woman', 2);
                    }
                    elseif ($service_for[$i] == '3') {
                        $checkService->where('is_kids', 2);
                    }
                    if ($checkService->count() > 0) {
                        $job_fetch = $checkService->first();
                        $order_job = new OrderJobs();
                        $order_job->order_id = $order->id;
                        $order_job->job_id = $service_id[$i];
                        $order_job->service_for = $service_for[$i];
                        if ($service_for[$i] == '1') {
                            $order_job->amount = $job_fetch->man_price;
                            $total_amount += $job_fetch->man_price;
                        } elseif ($service_for[$i] == '2') {
                            $order_job->amount = $job_fetch->woman_price;
                            $total_amount += $job_fetch->woman_price;
                        }
                        elseif ($service_for[$i] == '3') {
                            $order_job->amount = $job_fetch->kids_price;
                            $total_amount += $job_fetch->kids_price;
                        }
                        $order_job->save();
                    }
                }
            }

            $order->amount = $total_amount;
            $advance_amount = 0;
            if ($total_amount > 0) {
                $advance_amount = (($total_amount * 20) / 100);
            }
            $order->advance_amount = $advance_amount;

            $api = new Api(config('services.razorpay.id'), config('services.razorpay.key'));
            $orders = $api->order->create(array(
                'receipt' => $order->id,
                'amount' => $advance_amount * 100,
                'currency' => 'INR',
            ));

            $order->payment_request_id = $orders['id'];
            $order->save();

            $payment_data = [
                'key_id' => config('services.razorpay.id'),
                'amount' => $advance_amount * 100,
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
                    'advance_amount' => $advance_amount,
                    'payment_data' => $payment_data,
                ],
            ];

            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Something Went Wrong Please Try Again',
                'error_code' => false,
                'error_message' => null,
            ];
            return response()->json($response, 200);
        }
    }


    public function paymentVerify(Request $request)
    {
        $validator =  Validator::make($request->all(), [
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
            $user = Client::find($order->vendor_id);
            if ($user->firsbase_token) {
                $title = "Dear Vendor : A User Placed An Order With Order Id $order->id";
                PushHelperVendor::notification($user->firsbase_token, $title, $user->id, 2);
            }

            return response()->json($response, 200);
        } else {
            $response = [
                'status' => false,
                'message' => 'Payment Failed',
            ];
            return response()->json($response, 200);
        }
    }

    private function signatureVerify($order_id, $payment_id, $signature)
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
