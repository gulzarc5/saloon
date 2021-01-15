<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Customer;
use App\Models\JobCategory;
use App\Models\Order;
use App\Models\PasswordRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function passwordRequest()
    {
        return view('admin.password_request');
    }

    public function passwordRequestAjax(Request $request){
        return datatables()->of(PasswordRequest::orderBy('id','desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if (!empty($row->user_type == '1')) {
                    $btn ='<a href="'.route('admin.customer_edit',['id'=>$row->user_id]).'" class="btn btn-info btn-sm" target="_blank">View</a>
                    <a href="'.route('admin.user_change_password_form',['user_id'=>$row->user_id,'user_type'=>1,'request_id'=>$row->id]).'" class="btn btn-danger btn-sm" target="_blank">Reset Password</a>';
                }else{
                    $btn ='<a href="'.route('admin.client_details',['client_id'=>$row->user_id]).'" class="btn btn-info btn-sm" target="_blank">View</a>
                    <a href="'.route('admin.user_change_password_form',['user_id'=>$row->user_id,'user_type'=>2,'request_id'=>$row->id]).'" class="btn btn-danger btn-sm" target="_blank">Reset Password</a>';
                }
                
                return $btn;
            })
            ->addColumn('name', function($row){
                if (!empty($row->user_type == '1')) {
                    return $row->customer->name;
                }else{
                    return $row->client->name;
                }
            })
            ->addColumn('mobile', function($row){
                if (!empty($row->user_type == '1')) {
                    return $row->customer->mobile;
                }else{
                    return $row->client->mobile;
                }
            })
            ->rawColumns(['action','name','mobile'])
            ->make(true);
    }

    public function changePasswordForm($user_id,$user_type,$request_id)
    {
        if($user_type == '1'){
            $user = Customer::findOrFail($user_id);
        }else {
            $user = Client::findOrFail($user_id);
        }
        return view('admin.users.user_change_password',compact('user','user_type','request_id'));
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|numeric',
            'request_id' => 'required|numeric',
            'user_type' => 'required|numeric|in:1,2,3',
            'new_password' => 'required|string|min:8|same:confirm_password',
        ]);

        $user_id = $request->input('user_id');
        $user_type = $request->input('user_type');
        $request_id = $request->input('request_id');
        if ($user_type == '1') {
            $user = Customer::findOrFail($user_id);
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
        } else {
            $user = Client::findOrFail($user_id);
            $user->password = Hash::make($request->input('new_password'));
            $user->save();
        }

        $password_request = PasswordRequest::findOrFail($request_id);
        $password_request->status = 2;
        $password_request->save();

        return redirect()->back()->with('message','User Password Chqanged Succeddfully');
        
    }

}
