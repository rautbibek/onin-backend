<?php
namespace App\Services;


class SendSmsService{

   public static function sendSms($mobile,$message){ 
       
    $authKey= config('services.sms.sms_api_key');
    $senderId='31001';
    $data = array(
      'auth_token' => $authKey,
      'to' =>$mobile,
      'text'=>$message,
    );
    //return $data;


    $url = config('services.sms.sms_api_url') ;
    $ch = curl_init();

    curl_setopt_array($ch, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST =>true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $data,
    ));

    $response = curl_exec($ch);
    $err = curl_error($ch);

    if ($err) {
    echo "cURL Error #:" . $err;
    }
    curl_close($ch);
    return json_decode($response);
   }
}