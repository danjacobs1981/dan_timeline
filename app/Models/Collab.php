<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collab extends Model
{

    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'timeline_id', 
        'email'
    ];

}
