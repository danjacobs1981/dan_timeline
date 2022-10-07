<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Timeline;
use Config;

class TimelineController extends Controller
{
    public function show(Timeline $timeline) 
    {

        // set head items
        Config::set('constants.head.title', 'Timeline: '.$timeline->title);
        Config::set('constants.head.link_canonical', '/'.$timeline->id.'/'.$timeline->slug);
        
        return view('layouts.timeline.pages.timeline', ['timeline' => $timeline, 'temp_map' => 1, 'temp_comments' => 1, 'temp_filters' => 1, 'temp_tags' => 1]);

    }
}