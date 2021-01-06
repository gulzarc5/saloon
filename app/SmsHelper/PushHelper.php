<?php
namespace App\SmsHelper;

class PushHelper {
    public static function notification($token, $title,$user_id,$user_type)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token=$token;

        $notification = [
            'title' => $title,
            'sound' => true,
        ];
        
        $extraNotificationData = ["message" => $notification,"user_id" =>$user_id,'user_type'=>$user_type];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];
        
        $headers = [
            'Authorization: key=AAAAe00Vt4A:APA91bFBUWttYfBVXtxLquzF4_aBRYl6EIEPKx3AAJitrEwjxLNu476gw7O5Rwl_-vdVjKDgJ5xFtcX4UrZfWUtJvgeQTx4BO3v5YW1VPYZwai3ripexx9iwraDqq1cEnXjZa7VFy_kk',
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