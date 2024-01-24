<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    use HasFactory;

    protected $fillable = [
        'id', 
        'timeline_id', 
        'tag',
        'color',
        'image',
        'icon',
        'group_id'
    ];

    protected $hidden = ['pivot'];

    /**
     * The group that belong to the tag.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * The events that belong to the tag.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'tag_event');
    }

}
