<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    use HasFactory;

    /**
     * The events that belong to the tag.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'tag_event');
    }

    public $incrementing = false;

    protected $fillable = [
        'id', 
        'timeline_id', 
        'tag'
    ];

}
