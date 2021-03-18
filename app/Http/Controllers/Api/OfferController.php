<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\AdminCommission;
use App\Models\Coupon;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    { 
        $data = [
            'admin_commissions' => AdminCommission::all(),
            'coupons' => Coupon::where('status',1)->get(),
            'offers' => OfferResource::collection(Offer::where('status',1)->get()),
        ];

        $response = [
            'status' => true,
            'message' => 'Commission & Charges List',
            'data' => $data,
        ];
        return response()->json($response, 200);
    }
}
