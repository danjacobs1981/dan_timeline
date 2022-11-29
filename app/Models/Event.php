<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 
        'id_timeline', 
        'title', 
        'date_type', 
        'date_none', 
        'date_year', 
        'date_month', 
        'date_day', 
        'date_time', 
        'date_unix', 
        'date_unix_gmt',
        'location_lat',
        'location_lng',
        'location',
        'location_geo',
        'location_geo_street',
        'location_show',
        'location_tz',
        'location_tz_error',
        'order_ny',
        'order_ym',
        'order_md',
        'order_dt',
        'order_period',
        'order_overall',
    ];

}
