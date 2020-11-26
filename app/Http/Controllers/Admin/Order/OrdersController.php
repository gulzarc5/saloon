<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(){
        return view('admin.order.index');
    }

    public function refund(){
        return view('admin.order.refund');
    }
}
