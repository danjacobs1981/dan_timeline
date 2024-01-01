<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Save extends Model
{

    use HasFactory;

    public $timestamps = false; 

    public $incrementing = false;

    protected $fillable = [
        'timeline_id', 
        'user_id'
    ];

}
