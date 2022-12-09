<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{

    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 
        'timeline_id', 
        'tags', 
        'start', 
    ];

}
