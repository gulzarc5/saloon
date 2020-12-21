<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\InvoiceSetting;
use App\Models\Order;
use App\Models\OrderJobs;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(){
        $orders = Order::orderBy('id','desc')->paginate(20);
        return view('admin.order.index',compact('orders'));
    }

    public function refund(){
        return view('admin.order.refund');
    }

    public function orderDetails($order_id)
    {
        $order = Order::findOrFail($order_id);
        $invoice_setting = InvoiceSetting::find(1);
        $orderDetails = OrderJobs::where('order_id',$order->id)->get();
        return view('admin.order.order_details',compact('order','invoice_setting','orderDetails'));
    }

    public function acceptOrder($order_id,$status)
    {
        //status 2 = Accepted, 5 = Cancelled
        $order = Order::findOrFail($order_id);
        if ($status == '2' || $status == '5') {
            $order->order_status =$status;
            $order->save();
        }
        return 1;        
    }

    public function orderCancel($order_id,$bank_account_id)
    {
        # code...
    }
}
