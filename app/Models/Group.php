<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    use HasFactory;

    protected $fillable = [
        'id', 
        'timeline_id', 
        'group'
    ];

    /**
     * The timeline that the group belongs to.
     */
    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    /**
     * The tags that belong to the group.
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

}
