<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    use HasFactory;

    protected $fillable = [
        'parent_id', 
        'user_id', 
        'timeline_id', 
        'event_id', 
        'comment'
    ];

    /**
     * Get the timeline that owns the comment.
     */
    public function timeline()
    {
        return $this->belongsTo(Timeline::class);
    }

    /**
     * Get the event that owns the comment.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the author/user that owns the comment.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the replies of the comment.
     */
    public function replies() 
    {
        return $this->hasMany('App\Comment', 'parent_id');
    }

}
