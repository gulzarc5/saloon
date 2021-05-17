<?php
namespace App\SmsHelper;

use DB;
use Auth;
use Carbon\Carbon;

class Sms 
{
    public static function smsSend($mobile,$sms)
    {
        $username="Salonease";
        $api_password="9aea6qel249zytk4d";
        $sender="SAEASE";
        $domain="http://sms.webinfotech.co";
        $priority="4";// 11-Enterprise, 12- Scrub
        $method="GET";

        $mobile=$mobile;
        $message=$sms;

        $username=urlencode($username);
        $api_password=urlencode($api_password);
        $sender=urlencode($sender);
        $message=urlencode($message);

        $sms = urlencode($sms);
        
        $parameters="username=$username&api_password=$api_password&sender=$sender&to=$mobile&message=$message&priority=$priority&e_id=1201159421346420049&t_id=1207162091323213799";

        $url="$domain/pushsms.php?".$parameters;

        $ch = curl_init($url);

        if($method=="POST")
        {
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$parameters);
        }
        else
        {
            $get_url=$url."?".$parameters;

            curl_setopt($ch, CURLOPT_POST,0);
            curl_setopt($ch, CURLOPT_URL, $get_url);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
        curl_setopt($ch, CURLOPT_HEADER,0);  // DO NOT RETURN HTTP HEADERS 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  // RETURN THE CONTENTS OF THE CALL
        $return_val = curl_exec($ch);
        return $return_val;
    }
}