<?php
namespace App\SmsHelper;

use App\Models\Client;
use App\Models\Customer;

class MessagePush {
    public static function notification($title,$message,$user_type,$vendor_type=null)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        if ($user_type == 'C') {
            $app_ids = Customer::select('firsbase_token')->whereNotNull('firsbase_token')->get()->map(function ($data) {
                return $data->firsbase_token;
            });
        } else {
            if ($vendor_type) {
                if ($vendor_type == 'F') {
                    $app_ids = Client::select('firsbase_token')->where('clientType',1)->whereNotNull('firsbase_token')->get()->map(function ($data) {
                        return $data->firsbase_token;
                    });
                } else {
                    $app_ids = Client::select('firsbase_token')->where('clientType',2)->whereNotNull('firsbase_token')->get()->map(function ($data) {
                        return $data->firsbase_token;
                    });
                }
                
            }else{
                $app_ids = Client::select('firsbase_token')->whereNotNull('firsbase_token')->get()->map(function ($data) {
                    return $data->firsbase_token;
                });
            }
        }
       
        if (isset($app_ids) && count($app_ids) > 0) {
            $notification = [
                'title' => $title,
                'message' => $message,
                'sound' => true,
            ];
    
            $fcmNotification = [
                'registration_ids' => $app_ids, //multple token array
                // 'to'        => $token, //single token
                'data' => $notification,
            ];
            
            $headers = [
                'Authorization: key=AAAAYG6SOgw:APA91bFXa1IcgbkRwhWo8BiYbrKBoAGvIm8RGKI4xK1_3-0HBEGNxlWY369g7KEbmVAlEOLiacMoZkRJV7APe-HO-VJEgEAZBsGRFfrYJjt1z45b20sJSVQTYtxHfoePYLAvc7sBfe5F',
                'Content-Type: application/json'
            ];
    
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
            $result = curl_exec($ch);
            curl_close($ch);
            dd($result);
            return $result;
        }
dd(1);
       
    }
}