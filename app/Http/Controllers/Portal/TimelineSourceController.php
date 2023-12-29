<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Source;
use Carbon\Carbon;

use Image;

class TimelineSourceController extends Controller
{

    /**
     * Display a listing of the source.
     */
    public function index(Timeline $timeline, Request $request)
    {

        //dd($request->placement);
        /*$source_ids = null;
        if($request->event_id) {
            $source_ids = Event::where('timeline_id', $timeline->id)->find($request->event_id)->sourcesIDs()->toArray();
        }
        */

        // get any sources that have been added
        $sources_added = [];
        if($request->event_id) {
            //dd($event->sources()->find($request->event_id)->sourcesIDs()->all());
            $sources_added = Event::where('timeline_id', $timeline->id)->find($request->event_id)->sourcesIDs()->all();
        }

        $timeline_sources = $timeline->sources;

        if ($timeline_sources->count()) {

            $sources_html = view('layouts.portal.ajax.timeline.sources', ['timeline_sources' => $timeline_sources])->render();
            $sources_count = 'A total of '.$timeline_sources->count().' source(s) are available.';

        } else {

            $sources_html = null;
            $sources_count = 'Add a source to get started!';

        }

        return response()->json(array(
            'success' => true,
            'sources_html' => $sources_html,
            'sources_count' => $sources_count,
            'sources_added' => $sources_added
        ));

    }

    /**
     * Show the form for creating a new source.
     */
    public function create(Timeline $timeline, Request $request)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Create Source';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Create Source', 'form' => 'formSourceCreateEdit');
            $route = 'layouts.portal.pages.timeline.source.create-edit';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline'));

        } else {

            abort(401);

        }

    }

    /**
     * Store a newly created source in storage.
     */
    public function store(Timeline $timeline, Request $request)
    {

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                $data = $request->validate(
                    [
                        'url' => 'required|url:http,https',
                        'title' => [
                            'required',
                            'string',
                            'max:255',
                            Rule::unique('sources')->where(function ($query) use ($request){
                                $query->where('url', $request['url']);
                                $query->where('title', $request['title']);
                            })
                        ],
                        'fa_icon' => 'nullable',
                    ],
                    $messages = [
                        'title.unique' => 'A source already exists with this URL and title. You can use the same URL, just give this source a different title.',
                    ]
                );

                // set the icon
                $icon = setFAIcon($data['url']);
                
                if ($icon) {
                    $data['fa_icon'] = $icon;
                }

                $data['id'] = helperUniqueId('sources');
                $data['timeline_id'] = $timeline_id;

                // add the source
                Source::create($data);

                return response()->json([
                    'status'=> 200,
                    'message' => 'Source created successfully',
                    'source_id' => $data['id']
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }

    }

    /**
     * Display the specified source.
     */
    public function show(string $id): never
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified source.
     */
    public function edit(Timeline $timeline, Source $source)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $event_count = $source->events()->count();

            $modal_title = 'Edit Source';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Update Source', 'form' => 'formSourceCreateEdit');
            $route = 'layouts.portal.pages.timeline.source.create-edit';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'source', 'event_count'));

        } else {

            abort(401);

        }

    }

    /**
     * Update the specified source in storage.
     */
    public function update(Timeline $timeline, Source $source, Request $request)
    {

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                if ($request->title != $source->title || $request->url != $source->url) { // check something has changed

                    $data = $request->validate(
                        [
                            'url' => 'required|url:http,https',
                            'title' => [
                                'required',
                                'string',
                                'max:255',
                                Rule::unique('sources')->where(function ($query) use ($request, $source){
                                    $query->where('url', $request['url']);
                                    $query->where('title', $request['title']);
                                    $query->where('id', '!=', $source->id);
                                })
                            ],
                            'fa_icon' => 'nullable',
                        ],
                        $messages = [
                            'title.unique' => 'A source already exists with this URL and title. You can use the same URL, just give this source a different title.',
                        ]
                    );
    
                    // set the icon
                    $icon = setFAIcon($data['url']);
                    
                    if ($icon) {
                        $data['fa_icon'] = $icon;
                    }
    
                    // update the event
                    Source::where('timeline_id', $timeline_id)->where('id', $source->id)->update($data);

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Source updated successfully',
                        'source_id' => $source->id
                    ]);
    
                } else {

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Source remains the same',
                        'source_id' => $source->id
                    ]);

                }

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }

    }

    /**
     * Remove the specified source from storage.
     */
    public function destroy(string $id): never
    {
        abort(404);
    }

}

function setFAIcon($url) {
    $icon = null;
    // set icon based on extension
    $extension = pathinfo($url, PATHINFO_EXTENSION);
    if ($extension) {
        if ($extension == 'pdf') {
            $icon = 'fa-solid fa-file-pdf';
        }
    }
    if ($icon == null) {
        preg_match('@^(https?://)?([a-z0-9_-]+\.)*([a-z0-9_-]+)\.[a-z]{2,6}(/.*)?$@i', $url, $match);
        $domain = $match[3];
        // set icon based on specific domains
        if ($domain == 'youtu') {
            $icon = 'fa-brands fa-youtube';
        } else if ($domain == 'fb') {
            $icon = 'fa-brands fa-facebook';
        } else if ($domain == 'x') {
            $icon = 'fa-brands fa-x-twitter';
        } else if ($domain == 'bbc') {
            $icon = 'fa-regular fa-newspaper';
        }
        if ($icon == null) {
            // set icon based on domain (if in FA brands array)
            $brandsArray = \App\Data::FABrands();
            if (in_array($domain, $brandsArray, TRUE)) { 
                $icon = 'fa-brands fa-'.$domain;
            }
        }
    }
    return $icon;
}