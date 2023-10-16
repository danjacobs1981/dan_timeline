<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Collab;
use App\Models\Select;

use Carbon\Carbon;
use Carbon\CarbonInterface;

  
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

function checkCanViewTimeline($timeline_user_id, $timeline_id) 
{
    if(Auth::check() && auth()->user()->hasVerifiedEmail()) {
        if ($timeline_user_id === auth()->user()->id) {
            // if user owns the timeline
            return true;
        } else if (Select::where('timeline_id', $timeline_id)->where('email', auth()->user()->email)->exists()) {
            // if user is in the privately selected list
            return true;
        } else if (Collab::where('timeline_id', $timeline_id)->where('email', auth()->user()->email)->exists()) {
            // if user is collab on the timeline
            return true;
        }
    }
}
