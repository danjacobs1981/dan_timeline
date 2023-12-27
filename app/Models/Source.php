<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Source extends Model
{

    use HasFactory;

    /**
     * Get the timeline that owns the source.
     */
    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    /**
     * The events that belong to the source.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'source_event');
    }

    public $incrementing = false;

    protected $fillable = [
        'id', 
        'timeline_id', 
        'url', 
        'title', 
        'fa_icon',
    ];

}