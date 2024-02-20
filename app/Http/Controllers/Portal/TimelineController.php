<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

        /*$this->validate($request,[
            'title' => 'required|max:250', // this needs to be decent validation for title
        ]);*/

        $data = $request->validate(
            [
                'title' => [
                    'required',
                    'string',
                    'max:250',
                    'unique:timelines,title'
                ],
                'comment' => 'boolean',
                'comment_event' => 'boolean',
            ],
            $messages = [
                'title.required' => 'The timeline requires a title',
                'title.max' => 'The timline title must be 250 characters or less',
                'title.unique' => 'A timeline with this title already exists'
            ]
        );

        $adverts = 1;
        $tagging = 0;

        if (auth()->user()->premium) {
            $adverts = 0;
            $tagging = 1;
        }

        $data['id'] = $timeline_id;
        $data['user_id'] = auth()->user()->id;
        $data['slug'] = Str::slug($data['title'], "-");
        $data['adverts'] = $adverts;
        $data['tagging'] = $tagging;
        
        Timeline::create($data);
    
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
    public function edit(Timeline $timeline)
    {

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
        // this isn't required as AJAX is used for saving different sections (TimelineEditController)

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timeline $timeline)
    {
        
        if ($timeline && $timeline->user_id === auth()->user()->id) {

            // deletes timeline event images
            if (Storage::exists('public/images/timeline/'.$timeline->id)) {
                Storage::deleteDirectory('public/images/timeline/'.$timeline->id);
            }

            $timeline->delete(); 
            
            return redirect('/timelines')->with('action', 'Timeline successfully deleted');

        } else {

            return redirect('/timelines')->with('action', 'This timeline cannot be deleted')->with('type', 'warning');

        }

    }
}
