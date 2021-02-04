<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Customer;
use App\Models\State;
use App\Models\City;
use App\Models\Wallet;
use App\Models\WalletHistory;

class UsersController extends Controller
{
    public function customerList(){
        return view('admin.users.customer');
    }

    public function customerListAjax(Request $request){
        return datatables()->of(Customer::orderBy('id','desc')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn ='<a href="'.route('admin.customer_edit',['id'=>$row->id]).'" class="btn btn-info btn-sm" target="_blank">View</a>
                <a href="'.route('admin.customer_wallet_history',['user_id'=>$row->id]).'" class="btn btn-success btn-sm" target="_blank">Wallet history</a>';
                if ($row->status == '1') {
                    $btn .='<a href="'.route('admin.customer_status_update',['id'=>$row->id,2]).'" class="btn btn-danger btn-sm" >Disable</a>';
                } else {
                    $btn .='<a href="'.route('admin.customer_status_update',['id'=>$row->id,1]).'" class="btn btn-primary btn-sm" >Enable</a>';
                }
                return $btn;
            })
            ->addColumn('wallet', function($row){
                $btn = $row->wallet->amount ?? 0;
                return $btn;
            })
            ->rawColumns(['action','wallet'])
            ->make(true);
    }

    public function customerEdit($id){
        $customer = Customer::find($id);
        return view('admin.users.customer_edit',compact('customer'));
    }

    public function customerUpdate(Request $request,$id){
        $this->validate($request, [
            'name' => 'required',
            'mobile' => 'required|unique:customers,id,'.$id,
            'email' => 'unique:customers,id,'.$id,
            'gender' => 'required',
            'state' => 'required',
            'city' => 'required',
        ]);

        $customer = Customer::find($id);
        if ($customer) {
            $customer->name = $request->input('name');
            $customer->gender = $request->input('gender');
            $customer->email = $request->input('email');
            $customer->mobile = $request->input('mobile');
            $customer->dob = $request->input('dob');
            $customer->state = $request->input('state');
            $customer->city = $request->input('city');
            $customer->address = $request->input('address');
            $customer->pin = $request->input('pin');
            $customer->save();
        }
        return redirect()->back()->with('message','Customer Details Updated Successfully');
    }

    public function updateCustomerStatus($id,$status){
        $state = Customer::find($id);
        $state->status = $status;
        $state->save();
        return redirect()->back();
    }

    public function walletHistory($user_id)
    {
        $wallet = Wallet::where('user_id', $user_id)->first();

        $wallet_history = WalletHistory::where('wallet_id',$wallet->id)->latest()->limit(50)->get();
        return view('admin.users.wallet_history',compact('wallet_history'));
    }
}
