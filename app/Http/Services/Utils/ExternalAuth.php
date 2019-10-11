<?php

namespace App\Http\Services\Utils;

use Laravel\Lumen\Routing\Controller as BaseController;

class ExternalAuth extends BaseController
{
  public static function getExternalUser($token) {
    $curl = curl_init();	
    curl_setopt_array($curl, array(
      CURLOPT_URL => \env('EXTERNAL_AUTH'),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Content-Type: application/json; charset=utf-8',
        'Authorization: '.$token
      ),
    ));
  
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        return $data;
    }
    return null;
  }
}