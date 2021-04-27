<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\AdminCommission;
use App\Models\Coupon;
use App\Models\Offer;
use App\Models\Order;
use App\Services\CouponCheckService;
use App\Services\OfferCheckService;
use Validator;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    { 
        $customer_order = Order::where('customer_id',$request->user()->id)
                ->where('payment_status',2)
                ->where('order_status',"!=",5)->count();
        if ( $customer_order > 0) {
            $coupon = Coupon::where('status',1)->where('type',2)->get();
        }else{
            $coupon = Coupon::where('status',1)->where('type',1)->get();
        }
        $data = [
            'admin_commissions' => AdminCommission::all(),
            'coupons' => $coupon,
            'offers' => OfferResource::collection(Offer::where('status',1)->get()),
        ];

        $response = [
            'status' => true,
            'message' => 'Commission & Charges List',
            'data' => $data,
        ];
        return response()->json($response, 200);
    }

    public function offerCheck(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'offer_id' => 'required',
            'job_id' => 'required',
            'vendor_id' =>  'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Validation Error',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $user_id = $request->user()->id; 
        $vendor_id = $request->input('vendor_id');
        $offer_id = $request->input('offer_id');
        $offer_job_id = $request->input('job_id');
        $offer_check = OfferCheckService::checkOffer($offer_id,$offer_job_id,$vendor_id,$user_id);
        
        return response()->json($offer_check['data'], 200);
        
    }
    public function couponCheck(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'coupon_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => false,
                'message' => 'Required Field Can not be Empty',
                'error_code' => true,
                'error_message' => $validator->errors(),
            ];
            return response()->json($response, 200);
        }
        $user_id = $request->user()->id; 
        $coupon_id = $request->input('coupon_id');
        $offer_check = CouponCheckService::checkCoupon($coupon_id,$user_id);        
        return response()->json($offer_check['data'], 200);        
    }
}
