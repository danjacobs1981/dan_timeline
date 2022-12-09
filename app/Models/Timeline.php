<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use HasFactory;
    
    /**
     * Get the events for the timeline.
    */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get the tags for the timeline.
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

}
