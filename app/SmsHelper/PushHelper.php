<?php
namespace App\SmsHelper;

class PushHelper {
    public static function notification($token, $title,$user_id,$user_type)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token=$token;

        $notification = [
            'message' => $title,
            "user_id" =>$user_id,
            'user_type'=>$user_type,
            'sound' => true,
        ];
        

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'data' => $notification
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

        return $result;
    }
}