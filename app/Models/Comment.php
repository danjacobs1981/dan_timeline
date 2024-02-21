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

    protected $hidden = ['pivot'];

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
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Get the likes of the comment.
     */
    public function likes() 
    {
        return $this->hasMany(CommentsLike::class);
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function likedByUser()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

}
