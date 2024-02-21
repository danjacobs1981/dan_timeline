<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentsLike extends Model
{

    use HasFactory;

    public $timestamps = false; 

    public $incrementing = false;

    protected $fillable = [
        'comment_id', 
        'user_id'
    ];

}
