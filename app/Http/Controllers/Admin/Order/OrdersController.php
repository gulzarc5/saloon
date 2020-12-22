<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\InvoiceSetting;
use App\Models\Order;
use App\Models\OrderJobs;
use App\Models\RefundInfo;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(){
        $orders = Order::orderBy('id','desc')->paginate(20);
        return view('admin.order.index',compact('orders'));
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

    public function orderCancel($order_id,$is_refund,$bank_account_id=null)
    {
        //is_refund 1 = No, 2 = yesOrders
        $order = Order::findOrFail($order_id);        
        $order->order_status =5;
        if($order->save()){
            if ($is_refund == '2') {
                $refund = new RefundInfo();
                $refund->order_id = $order->id;
                $refund->account_id = $bank_account_id;
                $refund->amount = $order->advance_amount;
                if ($refund->save()) {
                    $order->refund_request = 2;
                    $order->save();
                }
            }
        }
        return 1;  
    }

    public function orderReSchedule($order_id,$schedule_date)
    {
        $order = Order::findOrFail($order_id); 
        $order->order_status =3;
        $order->service_time = $schedule_date;
        $order->save();
        return 1;
    }

    public function orderSearch(Request $request)
    {
        $this->validate($request, [
            'search_key' => 'required'
        ]);
        $order_id = $request->input('search_key');
        $orders = Order::where('id',$order_id)->paginate(20);
        return view('admin.order.index',compact('orders'));
    }
}
