<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'published',
        'privacy',
        'title',
        'slug',
        'map',
        'comments',
        'comments_event',
        'filter',
        'social',
        'collab',
        'profile',
        'adverts',
    ];
    
    /**
     * Get all events for the timeline.
    */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get all likes for the timeline.
    */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function likedByUser()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    /**
     * Get all saves for the timeline.
    */
    public function saves()
    {
        return $this->hasMany(Save::class);

    }

    public function savedByUser()
    {
        return $this->saves()->where('user_id', auth()->id())->exists();
    }

    /**
     * Get all sources for the timeline.
    */
    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    /**
     * Get all tags for the timeline.
    */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Get the user for the timeline.
    */
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the private selected users for the timeline.
    */
    public function privateUsers() {
        return $this->hasMany(Select::class, 'timeline_id')->select(['email as value']);
    }

}
