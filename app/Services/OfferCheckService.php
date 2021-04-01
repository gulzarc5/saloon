<?php
namespace App\Services;

use App\Models\Job;
use App\Models\Offer;
use App\Models\OfferSalon;
use App\Models\UserOfferCouponHistory;
use Carbon\Carbon;

class OfferCheckService
{
    public static function checkOffer($offer_id,$job_id,$vendor_id,$user_id){
        $offer = null;
        $offer_data = Offer::where('id',$offer_id)->where('status',1)->first();
        // Check offer Validation
        if ($offer_data) {
            //Check user Has Used This offer or not
            $offer_check_user = UserOfferCouponHistory::where('customer_id',$user_id)
            ->where('offer_id',$offer_id)
            ->where('offer_type',2)->count();

            if ($offer_check_user > 0) {
                $response = [
                    'status' => false,
                    'message' => 'Offer Is Expired',
                    'data' => null,
                ];
                return [
                    'status' => false,
                    'data' => $response,
                ];
            }
            
            //Check Offer validity.
           
            if ($offer_data->range_type == '2' && $offer_data->to_date < Carbon::today()->toDateString()) {
               
                $response = [
                    'status' => false,
                    'message' => 'Offer Is Expired',
                    'data' => null,
                ];
                return [
                    'status' => false,
                    'data' => $response,
                ];
            }
            
            // Check Offer is reached total user or not
            
            if ($offer_data->total_user < ($offer_data->offer_received_user+1)) {
                $response = [
                    'status' => false,
                    'message' => 'Offer Is Expired',
                    'data' => null,
                ];
                return [
                    'status' => false,
                    'data' => $response,
                ];
            }
        }else{
            $response = [
                'status' => false,
                'message' => 'Offer is Invalid',
                'data' => null,
            ];
            return [
                'status' => false,
                'data' => $response,
            ];
        }
        $job_offer = Job::find($job_id);
        if(!$job_offer){
            $response = [
                'status' => false,
                'message' => 'Offer Job Not Found',
                'data' => null,
            ];
            return [
                'status' => false,
                'data' => $response,
            ];
        }
        // Check Offer Is Applicable for this saloon or not
        $offer_salon = OfferSalon::where('offer_id', $offer_id)->where('client_id', $vendor_id)->count();
        if ($offer_salon == 0) {
            $response = [
                'status' => false,
                'message' => 'Offer Not Applicable To This Salon',
                'data' => null,
            ];
            return [
                'status' => false,
                'data' => $response,
            ];
        }

        // check offer is applicable for this category or not
        if (!empty($offer_data) && !empty($offer_data->third_category_id)) {
            if ($offer_data->third_category_id == $job_offer->last_category) {
                $offer = $offer_data;
            }else{
                $response = [
                    'status' => false,
                    'message' => 'Offer Not Applicable To This Category',
                    'data' => null,
                ];
                return [
                    'status' => false,
                    'data' => $response,
                ];
            }
        }elseif (!empty($offer_data) && !empty($offer_data->sub_category_id)) {
            if ($offer_data->sub_category_id==$job_offer->sub_category) {
                $offer = $offer_data;
            }else{
                $response = [
                    'status' => false,
                    'message' => 'Offer Not Applicable To This Category',
                    'data' => null,
                ];
                return [
                    'status' => false,
                    'data' => $response,
                ];
            }
        }elseif (!empty($offer_data) && !empty($offer_data->category_id) && $offer_data->category_id==$job_offer->job_category) {
            if ($offer_data->category_id==$job_offer->job_category) {
                $offer = $offer_data;
            }else{
                $response = [
                    'status' => false,
                    'message' => 'Offer Not Applicable To This Category',
                    'data' => null,
                ];
                return [
                    'status' => false,
                    'data' => $response,
                ];
            }
        }else{
            $response = [
                'status' => false,
                'message' => 'Offer Not Applicable To This Category',
                'data' => null,
            ];
            return [
                'status' => false,
                'data' => $response,
            ];
        }
        $response = [
            'status' => true,
            'message' => 'Offer Data',
            'data' => $offer,
        ];
        return [
            'status' => true,
            'data' => $response,
        ];
    }

}