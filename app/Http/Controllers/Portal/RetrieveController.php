<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RetrieveController extends Controller
{

    public function title(Request $request)
    {

        // ajax for timeline settings
        if($request->ajax()){
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // this is insecure - needs certs?
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this is insecure - needs certs?
            curl_setopt($ch, CURLOPT_URL, $request->url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $data = curl_exec($ch);
            curl_close($ch);

            preg_match('/<title>(.+)<\/title>/', $data, $matches);

            if (isset($matches[1])) {
                $title = $matches[1];
                if ($title == 'Blocked' || $title == 'Just a moment...') { // list of non-good titles
                    return response()->json([
                        'title' => null,
                    ]);
                } else {
                    return response()->json([
                        'title' => $title,
                    ]);
                }
            } else {
                return response()->json([
                    'title' => null,
                ]);
            }
 
        }
        
    }

}
