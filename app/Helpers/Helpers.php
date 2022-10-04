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