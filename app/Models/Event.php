<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Event extends Model
{

    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 
        'timeline_id', 
        'title', 
        'date_type', 
        'date_year', 
        'date_month', 
        'date_day', 
        'date_time', 
        'date_unix', 
        'date_unix_gmt',
        'period',
        'period_short',
        'difference',
        'location_lat',
        'location_lng',
        'location_tz',
        'location_tz_error',
        'location_show',
        'location_geo_building',
        'location_geo_street',
        'location_geo_city',
        'location_geo_region',
        'location_geo_country',
        'location_geo',
        'location',
        'location_zoom',
        'image',
        'image_thumbnail',
        'image_large',
        'description',
        'order_ny',
        'order_ym',
        'order_md',
        'order_dt',
        'order_t',
        'order_overall',
    ];

    /**
     * Get the timeline that owns the event.
     */
    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    /**
     * The sources that belong to the event.
     */
    public function sources()
    {
        return $this->belongsToMany(Source::class, 'source_event');
    }

    // just the IDs of the sources
    public function sourcesIDs()
    {
        return $this->sources()->allRelatedIds();
    }

    /**
     * The tags that belong to the event.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_event');
    }

    // just the IDs of the tags
    public function tagsIDs()
    {
        return $this->tags()->allRelatedIds();
    }

}