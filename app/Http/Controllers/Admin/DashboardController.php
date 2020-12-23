<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Customer;
use App\Models\JobCategory;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardView()
    {
        $total_customers_count = Customer::where('status',1)->count();
        $total_freelauncer_count = Client::where('clientType',1)->count();
        $saloon_shop_count = Client::where('clientType',2)->count();
        $service_category_count = JobCategory::where('status',1)->count();
        $processing_orders_count = Order::where('payment_status','2')
        ->where(function ($q){
            $q->where('order_status','!=',4)
            ->where('order_status','!=',5);
        })
        ->count();
        
        $date = Carbon::now();
        $to_date = $date->toDateTimeString();
        $from_date = $date->subMonths(3)->toDateTimeString();

        $new_order_pie = $this->pieData($from_date,$to_date,1);
        $accepted_order_pie = $this->pieData($from_date,$to_date,2);
        $completed_order_pie = $this->pieData($from_date,$to_date,4);
        $cancel_order_pie = $this->pieData($from_date,$to_date,5);
        $total = ($new_order_pie+$accepted_order_pie+$completed_order_pie+$cancel_order_pie);
        $pie = [
            'new_order_pie' => ($total != 0) ? (round(($new_order_pie*100)/$total)) : 0,
            'accepted_order_pie' => ($total != 0) ? (round(($accepted_order_pie*100)/$total)) : 0,
            'completed_order_pie' => ($total != 0) ? (round(($completed_order_pie*100)/$total)) : 0,
            'cancel_order_pie' => ($total != 0) ? (round(($cancel_order_pie*100)/$total)) : 0,
        ];
        $chart = $this->chartData();

        $orders = Order::orderBy('id','desc')->limit(10)->get();

        return view('admin.dashboard',compact('saloon_shop_count','total_freelauncer_count','total_customers_count','service_category_count','processing_orders_count','pie','orders','chart'));
    }

    function pieData($from_date,$to_date,$status)
    {
        $data = Order::where('order_status',$status)->whereBetween('created_at',[$from_date,$to_date])->count();
        return $data;
    }

    function chartData(){
        $data[] = [
            'level' => Carbon::now()->format('Y-m'),
            'completed' => $this->chartQueryDelivered(Carbon::now()->month),
            'cancel' => $this->chartQueryCancel(Carbon::now()->month),
        ];

        for ($i=1; $i < 11; $i++) {
            $data[] = [
                'level' => Carbon::now()->subMonths($i)->format('Y-m'),
                'completed' => $this->chartQueryDelivered(Carbon::now()->subMonths($i)->month),
                'cancel' => $this->chartQueryCancel(Carbon::now()->subMonths($i)->month),
            ];
        }
        return $data;
    }

    function chartQueryDelivered($month){
        $delivered = Order::where('order_status',4) ->whereMonth('created_at', $month)->count();
        return $delivered;
    }

    function chartQueryCancel($month){
        $cancel = Order::where('order_status',5) ->whereMonth('created_at', $month)->count();
        return $cancel;
    }

}
