<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RefundInfo;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function refund(){
        $refunds = RefundInfo::orderBy('id','desc')->paginate(20);
        return view('admin.order.refund',compact('refunds'));
    }

    public function refundUpdate($refund_id)
    {
        $refund = RefundInfo::findOrFail($refund_id);
        $refund->refund_status = 2;
        if ($refund->save()) {
            $order = Order::findOrFail($refund->order_id);
            $order->refund_request = 3;
            $order->save();
        }
        return 1;
    }
}
