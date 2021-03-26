<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientSchedule;
use App\Models\Customer;
use App\Models\InvoiceSetting;
use App\Models\Job;
use App\Models\Order;
use App\Models\OrderJobs;
use App\Models\RefundInfo;
use App\Models\UserBankAccount;
use Illuminate\Http\Request;

use App\SmsHelper\PushHelper;
use App\SmsHelper\PushHelperVendor;
use Carbon\Carbon;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->where('vendor_cancel_status',1)->paginate(20);
        return view('admin.order.index', compact('orders'));
    }

    public function vendorCancelOrders()
    {
        $orders = Order::orderBy('id', 'desc')->where('vendor_cancel_status',2)->paginate(20);
        return view('admin.order.vendor_cancelled_order', compact('orders'));
    }

    public function vendorChangeForm($order_id)
    {
        $order = Order::find($order_id);
        return view('admin.order.vendor_change_form', compact('order'));
    }

    public function vendorCheck(Request $request)
    {
        $vendor_mobile = $request->input('mobile');
        $order_id = $request->input('order_id');

        $vendor = Client::where('mobile',$vendor_mobile)
        ->where('profile_status',2)
        ->where('verify_status',2)
        ->where('job_status',2)
        ->where('status',1)
        ->first();
        $order = Order::find($order_id);
        if ($vendor && $order) {

            $check_client_schedule = ClientSchedule::where('user_id',$order->vendor_id)
            ->where('date',Carbon::parse($order->service_time)->toDateString())->count();
            if ($check_client_schedule == 0) {
                $check_service_flag = true;
                $amount = 0;
                foreach ($order->services as $service) {
                    $vendor_service = $service->job;
                    $vendor_service_check = Job::where('user_id',$vendor->id)->where('status',1);
                    if ($vendor_service->last_category) {
                        $vendor_service_check->where('last_category',$vendor_service->last_category);
                    }
                    if ($vendor_service->sub_category) {
                        $vendor_service_check->where('sub_category',$vendor_service->sub_category);
                    }
                    if ($vendor_service->job_category) {
                        $vendor_service_check->where('job_category',$vendor_service->job_category);
                    }
                    if ($vendor_service_check->count() == 0) {
                        $check_service_flag = false;
                    }
                    $vendor_service_check=$vendor_service_check->first();
                    $amount += $service->quantity*$vendor_service_check->price;
                }
                if ($check_service_flag) {
                    $response = [
                        'status' => true,
                        'message' => 'Vendor Available',
                        'data' => [
                            'amount' => $amount,
                            'client' => $vendor,
                        ],
                    ];
                    return response()->json($response, 200);
                }else{
                    $response = [
                        'status' => false,
                        'message' => 'Sorry !! this order services not available in this vendor',
                        'data' => null,
                    ];
                    return response()->json($response, 200);
                }
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Vendor Not Available At This Date',
                    'data' => null,
                ];
                return response()->json($response, 200);
            }
            
        } else {
            $response = [
                'status' => false,
                'message' => 'Vendor Not Found',
                'data' => null,
            ];
            return response()->json($response, 200);
        }
        
    }

    public function vendorChange(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|numeric|digits:10',
            'order_id' => 'required|numeric',
        ]);
        $mobile = $request->input('mobile');
        $vendor = Client::where('mobile',$mobile)
        ->where('profile_status',2)
        ->where('verify_status',2)
        ->where('job_status',2)
        ->where('status',1)
        ->first();

        $order_id = $request->input('order_id');
        $vendor_check = $this->vendorCheck($request)->getContent();
        $vendor_check = json_decode($vendor_check, true);
        if ($vendor_check['status']) {
            $order = Order::findOrFail($order_id);
            foreach ($order->services as $service){
                if($new_service_id = $this->vendorServiceFetch($service->job,$order->vendor_id)){
                    $order_job = OrderJobs::findOrFail($service->id);
                    $order_job->job_id = $new_service_id;
                    $order_job->save();
                }
            }
            $order->vendor_id = $vendor->id;
            $order->order_status = 1;
            $order->save();
            $title = "Dear Vendor : A User Placed An Order With Order Id $order->id";
            PushHelperVendor::notification($vendor->firsbase_token, $title, $vendor->id, 2);

            $title = "Dear Customer : Your Vendor Change Request Accepted And Changed";
            PushHelper::notification($order->customer->firsbase_token, $title, $order->customer_id, 1);
            return back()->with('message',"Vendor Changed Successfully");
        } else {
            return back()->with('error',$vendor_check['message']);
        }
    }

    /**
     * vendor service fetch function used a chield function of vendor change
     * @return service_id 
     */
    private function vendorServiceFetch($vendor_service,$vendor_id)
    {
        $vendor_service_check = Job::where('user_id',$vendor_id)->where('status',1);
        if ($vendor_service->last_category) {
            $vendor_service_check->where('last_category',$vendor_service->last_category);
        }
        if ($vendor_service->sub_category) {
            $vendor_service_check->where('sub_category',$vendor_service->sub_category);
        }
        if ($vendor_service->job_category) {
            $vendor_service_check->where('job_category',$vendor_service->job_category);
        }

        $service = $vendor_service_check->first();
        return $service->id;
    }

    public function orderDetails($order_id)
    {
        $order = Order::findOrFail($order_id);
        $invoice_setting = InvoiceSetting::find(1);
        $orderDetails = OrderJobs::where('order_id', $order->id)->get();
        return view('admin.order.order_details', compact('order', 'invoice_setting', 'orderDetails'));
    }

    public function acceptOrder($order_id, $status)
    {
        //status 2 = Accepted, 5 = Cancelled
        $order = Order::findOrFail($order_id);
        if ($status == '2' || $status == '5') {
            $order->order_status = $status;
            $order->save();
        }
        if ($status == '2') {
            $title = "Dear Customer : Your Order is Accepted With Order Id : $order->id";
            $this->sendPushCustomer($order->customer_id, $title);

            //client Push
            $title = "Dear Client : Your Order is Accepted By Saloon Ease With Order Id : $order->id";
            $this->sendPushClient($order->vendor_id, $title);
        } elseif ($status == '3') {
            $title = "Dear Customer : Your Order is Rescheduled With Order Id : $order->id";
            $this->sendPushCustomer($order->customer_id, $title);

            //client Push
            $title = "Dear Client : Your Order is Rescheduled By Saloon Ease With Order Id : $order->id";
            $this->sendPushClient($order->vendor_id, $title);
        }
        elseif ($status == '4') {
            $title = "Dear Customer : Your Order is Completed With Order Id : $order->id";
            $this->sendPushCustomer($order->customer_id, $title);

            //client Push
            $title = "Dear Client : Your Order is Completed By Saloon Ease With Order Id : $order->id";
            $this->sendPushClient($order->vendor_id, $title);
        }
        elseif ($status == '5') {
            $title = "Dear Customer : Your Order is Cancelled With Order Id : $order->id";
            $this->sendPushCustomer($order->customer_id, $title);

            //client Push
            $title = "Dear Client : Your Order is Cancelled By Saloon Ease With Order Id : $order->id";
            $this->sendPushClient($order->vendor_id, $title);
        }

        return 1;
    }

    public function orderCancel($order_id, $is_refund, $bank_account_id = null)
    {
        //is_refund 1 = No, 2 = yesOrders
        $order = Order::findOrFail($order_id);
        $order->order_status = 5;
        if ($order->save()) {
            if ($is_refund == '2') {
                $user_account = UserBankAccount::where('user_id', $order->customer_id)->first();
                $refund = new RefundInfo();
                $refund->order_id = $order->id;
                $refund->account_id = $user_account->id;
                $refund->amount = $order->advance_amount;
                if ($refund->save()) {
                    $order->refund_request = 2;
                    $order->save();
                }
            }
        }

        $title = "Dear Customer : Your Order is Cancelled With Order Id : $order->id";
        $this->sendPushCustomer($order->customer_id, $title);

        //client Push
        $title = "Dear Client : Your Order is Cancelled By Saloon Ease With Order Id : $order->id";
        $this->sendPushClient($order->vendor_id, $title);

        return 1;
    }

    public function orderReSchedule($order_id, $schedule_date)
    {
        $order = Order::findOrFail($order_id);
        $order->order_status = 3;
        $order->service_time = $schedule_date;
        $order->save();

        $title = "Dear Customer : Your Order is Rescheduled With Order Id : $order->id";
        $this->sendPushCustomer($order->customer_id, $title);

        //client Push
        $title = "Dear Client : Your Order is Rescheduled By Saloon Ease With Order Id : $order->id";
        $this->sendPushClient($order->vendor_id, $title);

        return 1;
    }

    public function orderSearch(Request $request)
    {
        $this->validate($request, [
            'search_key' => 'required'
        ]);
        $order_id = $request->input('search_key');
        $orders = Order::where('id', $order_id)->paginate(20);
        return view('admin.order.index', compact('orders'));
    }

    private function sendPushCustomer($customer_id, $title)
    {
        $customer = Customer::findOrFail($customer_id);
        if (!empty($customer->firsbase_token)) {
            $client_type = 1;
            PushHelper::notification($customer->firsbase_token, $title, $customer->id, $client_type);
        }
    }

    private function sendPushClient($client_id, $title)
    {
        $client = Client::findOrFail($client_id);
        if (!empty($client->firsbase_token)) {
            $client_type = $client->clientType == '1' ? 2 : 3;
            PushHelperVendor::notification($client->firsbase_token, $title, $client->id, $client_type);
        }
    }
}
