<?php
namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\UserOfferCouponHistory;

class CouponCheckService
{
    public static function checkCoupon($coupon_id,$user_id){
        $coupon = Coupon::find($coupon_id);
        if ($coupon) {
            # Coupon is for new Customer or Old 
            if ($coupon->type == '1') {
                # If new user coupon Check This User Is New Or Old
                $customer_order = Order::where('customer_id',$user_id)
                ->where('payment_status',2)
                ->where('order_status',"!=",5)->count();
                if ($customer_order > 0) {
                    $response = [
                        'status' => false,
                        'message' => 'Coupon Code Is Invalid',
                        'data' => null,
                    ];
                    return [
                        'status' => false,
                        'data' => $response,
                    ];
                }
                # if new user check coupon is already applied or not
                $coupon_history = UserOfferCouponHistory::where('offer_type',1)
                ->where('customer_id',$user_id)
                ->where('offer_id',$coupon_id)
                ->count();
                if ($coupon_history > 0) {
                    $response = [
                        'status' => false,
                        'message' => 'Coupon Code Is Invalid',
                        'data' => null,
                    ];
                    return [
                        'status' => false,
                        'data' => $response,
                    ];
                }
            }

            $response = [
                'status' => true,
                'message' => 'Coupon Data',
                'data' => $coupon,
            ];
            return [
                'status' => true,
                'data' => $response,
            ];            
            
        } else {
            $response = [
                'status' => false,
                'message' => 'Coupon Not Found',
                'data' => null,
            ];
            return [
                'status' => false,
                'data' => $response,
            ];
        }
        
        
    }

}