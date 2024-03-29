<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Timeline;

class HomeController extends Controller
{
    public function show() 
    {

        $timelines = Timeline::where('privacy', 3)->take(10)->get();

        return view('layouts.web.pages.home')->with('timelines', $timelines);

    }
}