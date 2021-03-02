<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Customer;
use App\Models\InvoiceSetting;
use App\Models\Order;
use App\Models\OrderJobs;
use App\Models\RefundInfo;
use App\Models\UserBankAccount;
use Illuminate\Http\Request;

use App\SmsHelper\PushHelper;
use App\SmsHelper\PushHelperVendor;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('id', 'desc')->paginate(20);
        return view('admin.order.index', compact('orders'));
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
