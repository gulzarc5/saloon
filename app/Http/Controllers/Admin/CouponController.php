<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function couponList()
    {
        $coupons = Coupon::get();
        return view('admin.coupons.list',compact('coupons'));
    }

    function couponEdit($coupon_id)
    {
        $coupon = Coupon::findOrFail($coupon_id);
        return view('admin.coupons.coupon_edit', compact('coupon'));
    }

    public function couponUpdate(Request $request, $coupon_id)
    {
        $this->validate($request, [
            'amount' => 'required|numeric'
        ]);
        $coupon = Coupon::findOrFail($coupon_id);
        $coupon->amount = $request->input('amount');
        $coupon->save();

        return redirect()->back()->with('message','Coupon Updated Successfully');
    }
}
