<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'premium',
        'god',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always encrypt password when it is updated.
     *
     * @param $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Get the timelines owned by the user.
     */
    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    /**
     * Get the timelines that have been 'liked' by the user.
    */
    public function timelinesLiked()
    {
        return $this->belongsToMany(Timeline::class, 'likes', 'user_id', 'timeline_id');
    }

    /**
     * Get the timelines that have been 'saved' by the user.
    */
    public function timelinesSaved()
    {
        return $this->belongsToMany(Timeline::class, 'saves', 'user_id', 'timeline_id');
    }

}
