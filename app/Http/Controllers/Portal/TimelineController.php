<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Timeline;

class TimelineController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = auth()->user();
        $timelines = $user->timelines->sortBy('title');

        return view('layouts.portal.pages.timeline.index', compact('timelines'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('layouts.portal.pages.timeline.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $timeline_id = helperUniqueId('timelines');

        $this->validate($request,[
            'title' => 'required', // this needs to be decent validation for title
        ]);

        // adds all "request data" plus adds these specific things
        $request->request->add(['id' => $timeline_id, 'user_id' => auth()->user()->id, 'slug' => Str::slug($request->title, "-")]);
        
        Timeline::create($request->all());
    
        return redirect('/timelines/'.$timeline_id.'/edit')->with('action', 'Timeline created!')->with('helper', true); 

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): never
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $timeline = Timeline::get()->find($id);
        
        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $privateUsers = $timeline->privateUsers;
            
            return view('layouts.portal.pages.timeline.edit', compact('timeline', 'privateUsers'));

        } else {

            return redirect('/timelines')->with('action', 'This timeline cannot be edited')->with('type', 'warning');

        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): never
    {

        abort(404);
        // this isn't required now as AJAX is used
        
        /*$timeline = Timeline::find($id);

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $user_id = auth()->user()->id;
            $timeline->user_id = $user_id;
            $timeline->privacy = $request->privacy;
            $timeline->title = $request->title;
            $timeline->slug = Str::slug($request->title, "-");
            $timeline->comments = $request->comments;
            
            $timeline->save();

            return redirect('/timelines')->with('success', 'Successfully updated the timeline');

        } else {

            return redirect('/timelines')->with('action', 'This timeline cannot be updated')->with('type', 'warning');

        }*/

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $timeline = Timeline::find($id);

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $timeline->delete(); 
            
            return redirect('/timelines')->with('action', 'Timeline successfully deleted');

        } else {

            return redirect('/timelines')->with('action', 'This timeline cannot be deleted')->with('type', 'warning');

        }

    }
}
