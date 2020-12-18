<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
}
