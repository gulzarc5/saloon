<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientSchedule;
use App\Models\Job;
use App\Models\Order;
use App\Models\OrderJobs;
use App\Models\UserOfferCouponHistory;
use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\AdminCommissionService;
use App\Services\CouponCheckService;
use App\Services\OfferCheckService;
use App\Services\WalletAmountService;
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
            'vendor_id' => 'required|numeric',
            'offer_id' => 'nullable|numeric',
            'offer_job_id' => 'nullable|numeric',
            'coupon_id' => 'nullable|numeric',
            'is_wallet' => 'nullable|numeric|in:1,2', // 1 = wallet use, 2 = No
            'job_id' => 'required|array|min:1',
            'job_id.*' => 'required',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required',
            'address_id' => 'nullable|numeric',
            'service_time' => 'required',
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
        $user_id = $request->user()->id;
        $vendor_id = $request->input('vendor_id');
        $offer_id = $request->input('offer_id');
        $offer_job_id = $request->input('offer_job_id');
        $coupon_id = $request->input('coupon_id');
        $is_wallet = $request->input('is_wallet'); // 1 = yes else no
        $job_id = $request->input('job_id'); // Array Of Job Id
        $quantity = $request->input('quantity'); // Array Of Quantity
        $address_id = $request->input('address_id');
        $service_time = $request->input('service_time');

        //check service is available or not
        for ($i = 0; $i < count($job_id); $i++) {
            if (isset($job_id[$i]) && !empty($job_id[$i])) {
                $checkService = Job::where('id', $job_id[$i])->where('user_id',$vendor_id)->where('status', 1);
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

        $service_date_check = Carbon::parse(Carbon::now())->toDateString();
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
    
        //check Offer Id Available or Not
        $offer = null;
        if ($offer_id && $job_id) {
            $offer_check = OfferCheckService::checkOffer($offer_id,$offer_job_id,$vendor_id,$user_id);
            if ($offer_check['status'] == true) {
                $offer = $offer_check['data']['data'];
            }        
        }
        // coupon check
        $coupon = null;
        if (!$offer && $coupon_id) {
            $coupon_check = CouponCheckService::checkCoupon($coupon_id,$user_id);
            if ($coupon_check['status'] == true) {
                $coupon = $coupon_check['data']['data'];
            }  
        }
        ////////////// Validation End////////////////////


        $total_amount = 0;
        //Order Creation
        $order = new Order();
        $order->customer_id = $user_id;
        $order->vendor_id = $vendor_id;
        if ($address_id) {
            $order->customer_address_id = $address_id;
        }
        $order->service_time = $request->input('service_time');
        if ($order->save()) {
            
            for ($i = 0; $i < count($job_id); $i++) {
                if (isset($job_id[$i]) && !empty($job_id[$i]) && isset($quantity[$i]) && !empty($quantity[$i])) {
                    $service = Job::where('id', $job_id[$i])->where('user_id',$vendor_id)->where('status', 1)->first();
                    if ($service) {
                        $order_job = new OrderJobs();
                        $order_job->order_id = $order->id;
                        $order_job->job_id = $job_id[$i];
                        $order_job->quantity = $quantity[$i] ?? 1;
                        $order_job->mrp = $service->mrp;
                        if ($offer && $offer_job_id == $job_id[$i]) {
                            $order->discount = ($service->price - $offer->price);
                            $this->offerHistory($offer->id,$user_id,$order->id);
                        }
                        $order_job->amount = $service->price*$quantity[$i];                        
                        $order_job->save();
                        $total_amount += $order_job->amount;
                    }
                }
            }

            $order->amount = $total_amount;
            $advance_amount = 0;
            $total_amount = $total_amount - $order->discount;
            if ($total_amount == 1) {
                $order->advance_amount = $total_amount;
            }elseif ($total_amount > 0) {
                $admin_commission = AdminCommissionService::commissionFetch($total_amount);
                if ($admin_commission) {
                    $order->advance_amount = (($total_amount * $admin_commission->charge_amount) / 100);
                }
            }
            $order->save();

            //Check Wallet Pay Or No
            if ($is_wallet == 1) {
                $wallet = WalletAmountService::walletFetch($user_id);
                if ($wallet['status'] && ($order->advance_amount >= 1)) {
                    $wallet_amount = $wallet['data']->amount;
                    if ($wallet_amount > 0) {
                        if ($wallet_amount >= $order->advance_amount) {
                            $order->wallet_pay = $order->advance_amount;
                            $order->payment_method = 2;
                            $order->payment_status = 2;
                        }else{
                            $order->wallet_pay = $wallet['data']->amount;
                        }
                        $order->save();
                        $this->walletAmountDebit($order->wallet_pay,$wallet['data']->id);
                    }
                }
            }
            
            if ($order->wallet_pay == $order->advance_amount) {
                $response = [
                    'status' => true,
                    'message' => 'Order Place',
                    'error_code' => false,
                    'error_message' => null,
                    'data' => [
                        'order_id' => $order->id,
                        'payment_status' => 2, // 1 = pay online 2 = paid by wallet
                        'amount' => $order->amount,
                        'advance_amount' => $order->advance_amount,
                        'payment_data' => null,
                    ],
                ];
            } else {
                $api = new Api(config('services.razorpay.id'), config('services.razorpay.key'));
                $orders = $api->order->create(array(
                    'receipt' => $order->id,
                    'amount' => $order->advance_amount * 100,
                    'currency' => 'INR',
                ));

                $order->payment_request_id = $orders['id'];
                $order->online_pay = ($order->advance_amount - $order->wallet_pay);
                $order->save();
                $payment_data = [
                    'key_id' => config('services.razorpay.id'),
                    'amount' => ($order->advance_amount - $order->wallet_pay) * 100,
                    'order_id' => $orders['id'],
                    'name' => $order->customer->name,
                    'email' => $order->customer->email ?? null,
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
                        'amount' => $order->amount,
                        'advance_amount' => $order->advance_amount,
                        'payment_data' => $payment_data,
                    ],
                ];
    
            }  
            
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

    private function offerHistory($offer_id,$customer_id,$order_id){
        $offer_history = new UserOfferCouponHistory();
        $offer_history->customer_id = $customer_id;
        $offer_history->offer_id = $offer_id;
        $offer_history->order_id = $order_id;
        $offer_history->offer_type = 2;
        $offer_history->save();
        return true;
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

    private function walletAmountDebit($amount,$wallet_id){
        $wallet = Wallet::find($wallet_id);
        if ($wallet) {
            $wallet->amount = $wallet->amount - $amount;
            $wallet->save();
            $wallet_history = new WalletHistory();
            $wallet_history->wallet_id = $wallet->id;
            $wallet_history->transaction_type = 2;
            $wallet_history->amount = $amount;
            $wallet_history->total_amount = $wallet->amount;
            $wallet_history->comment = "Amount Debited Against Purchase";
            $wallet_history->save();
            return true;
        }
        return false;
    }
}
