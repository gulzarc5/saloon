<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminCommission;
use Illuminate\Http\Request;

class AdminCommissionController extends Controller
{
    public function commissionList()
    {
        $commissions = AdminCommission::get();
        return view('admin.admin_commission.list',compact('commissions'));
    }

    public function commissionEdit($com_id)
    {
        $commission = AdminCommission::findOrFail($com_id);
        return view('admin.admin_commission.commission_edit',compact('commission'));
    }

    public function commissionUpdate(Request $request, $commission_id)
    {
        $this->validate($request, [
            'from_amount' => 'required|numeric',
            'to_amount' => 'required|numeric',
            'charge_amount' => 'required|numeric',
        ]);
        $com = AdminCommission::findOrFail($commission_id);
        $com->from_amount = $request->input('from_amount');
        $com->to_amount = $request->input('to_amount');
        $com->charge_amount = $request->input('charge_amount');
        $com->save();
        return redirect()->back()->with('message','Admin Commission updated Successfully');
    }
}
