<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralSetting;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $data = ReferralSetting::get();
        return view('admin.referral.list',compact('data'));
    }

    public function referralEdit($referral_id)
    {
        $data = ReferralSetting::findOrFail($referral_id);
        return view('admin.referral.edit',compact('data'));
    }

    public function referralUpdate(Request $request,$referral_id)
    {
        $this->validate($request, [
            'amount' => 'required',
        ]);
        $data = ReferralSetting::findOrFail($referral_id);
        $data->amount = $request->input('amount');
        $data->save();
        return back()->with('message','Referral Commission Updated Successfully');
    }
}
