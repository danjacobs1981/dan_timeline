<?php

use Illuminate\Support\Facades\DB;
  
function helperUniqueId($table)
{
    $number = '';
    do {
        $number = rand(11111,99999);
    } while ( !empty(DB::table($table)->where('id', $number)->first(['id'])) );
    return $number;
}

function helperCurl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // this is insecure - needs certs?
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this is insecure - needs certs?
    $returnedJson = curl_exec($ch);
    curl_close($ch);
    return json_decode($returnedJson);
}