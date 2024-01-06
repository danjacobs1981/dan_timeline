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
     * The tags that belong to the group.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}
