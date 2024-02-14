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
        'image',
        'description',
        'map',
        'map_satellite',
        'comments',
        'comments_event',
        'tagging',
        'social',
        'collab',
        'profile',
        'adverts',
        'featured',
    ];
    
    /**
     * Get all events for the timeline (with tags).
    */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function eventsByTag($tags)
    {
        return $this->events()->whereRelationIn('tags', 'tags.id', $tags);
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
     * Get all groups for the timeline.
    */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Get all tags for the timeline.
    */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Get all tags for the timeline that are being used in events.
    */
    public function tagsUsed()
    {
        return $this->hasManyThrough(
            Tag::class,
            'App\Pivots\TagEvent',
            'timeline_id',
            'id',
            'id',
            'tag_id'
        );
    }

    /**
     * Get all tags for the timeline that are being used in events (and their groups).
    */
    public function tagsUsedGroups()
    {
        return $this->hasManyThrough(
            Tag::class,
            'App\Pivots\TagEvent',
            'timeline_id',
            'id',
            'id',
            'tag_id', 
        )->join('groups', 'groups.id', '=', 'tags.group_id')->select('tags.*', 'groups.*', 'groups.id as group_id', 'tags.id as tag_id');
    }

    /**
     * Get all sources for the timeline.
    */
    public function sources()
    {
        return $this->hasMany(Source::class);
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
