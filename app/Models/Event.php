<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Event extends Model
{

    use HasFactory;



    /**
     * Get the timeline that owns the event.
     */
    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    /**
     * The tags that belong to the event.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_event');
    }

    /*
    public function getDateHTMLAttribute()
    {
        if ($this->date_type === 1) {
            $dt = Carbon::create($this->date_year);
            return '<span>During</span> <span class="event-moment">'.$dt->format('Y').'</span>';
        } else if ($this->date_type === 2) {
            $dt = Carbon::create($this->date_year, $this->date_month);
            return '<span>During</span> <span class="event-moment">'.$dt->format('F').'</span> <span>'.$dt->format('Y').'</span>';
        } else if ($this->date_type === 3) {
            $dt = Carbon::create($this->date_year, $this->date_month, $this->date_day);
            return '<span>On</span> <span class="event-moment">'.$dt->format('l jS').'</span> <span>'.$dt->format('F').'</span> <span>'.$dt->format('Y').'</span>';
        } else if ($this->date_type === 4) {
            $dt = Carbon::createFromTimestamp($this->date_unix);
            return '<span>At</span> <span class="event-moment">'.$dt->format('h:ia').' ('.$this->location_tz.')</span> <span>on</span> <span>'.$dt->format('l jS').'</span> <span>'.$dt->format('F').'</span> <span>'.$dt->format('Y').'</span>';
        } else {
            return 'some clever words';
        }
    }
    */

    public function getDateISOAttribute()
    {
        if ($this->date_unix) {
            return Carbon::parse($this->date_unix)->toISOString();
        } else {
            return null;
        }
    }

    public $incrementing = false;

    protected $fillable = [
        'id', 
        'timeline_id', 
        'title', 
        'date_type', 
        'date_none', 
        'date_year', 
        'date_month', 
        'date_day', 
        'date_time', 
        'date_unix', 
        'date_unix_gmt',
        'period_none',
        'period_year',
        'period_month',
        'period_day',
        'period_time',
        'difference',
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
        'order_overall',
    ];

}