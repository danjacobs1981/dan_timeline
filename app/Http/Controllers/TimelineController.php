<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Source;
use App\Models\Tag;
use App\Models\Share;
use App\Models\Like;

use App\Mail\AmazonSESMail;
use Illuminate\Support\Facades\Mail;

use Config;

class TimelineController extends Controller
{
    
    public function show(Timeline $timeline, Request $request) 
    {

        // Mail::to('success@simulator.amazonses.com')->send(new AmazonSESMail());

        if ($timeline->privacy > 1 || checkCanViewTimeline($timeline->user_id, $timeline->id)) { 

            // set head items
            Config::set('constants.head.title', 'Timeline: '.$timeline->title);
            Config::set('constants.head.link_canonical', '/'.$timeline->id.'/'.$timeline->slug);

            if ($request->query('share')) {
                // dd($request->query('share'));
                // join the share table to the timeline table?
                // this will also add the class "event-start" if an initial event has been selected
            }

            return view('layouts.timeline.pages.timeline', ['timeline' => $timeline, 'temp_filters' => 1, 'temp_tags' => 1]);

        } else {

            // error "private timeline" page
            return view('errors.private');

        }

    }

    public function events(Timeline $timeline, Request $request) 
    {

        /*$selectedTags = array();

        if($request->share) {
            $share = Share::find($request->share)->where('timeline_id', $timeline->id)->first();
            if ($share->count()) {
                if ($share->tags) {
                    $selectedTags = array($share->tags);
                }
                if ($share->start) {
                    // do event_start thing too
                }
            }
        } else if ($request->tags === 'true') {
            $selectedTags = array(67890);
        }

        if ($selectedTags) {
            $events_ids = array();
            $tags = Tag::all();
            foreach ($selectedTags as $selectedTag) {
                $tag = $tags->find($selectedTag);
                if($tag) {
                    foreach ($tag->events as $event) {
                        array_push($events_ids, $event->id);
                    }
                }
            }
            $timeline_events = $timeline->events->whereIn('id', $events_ids); 
        } else {
            $timeline_events = $timeline->events;
        }*/

        if ($request->ajax()){
        
            $timeline_events = $timeline->events;

            if ($timeline_events->count()) {

                $events_html = view('layouts.timeline.ajax.events', ['timeline_events' => $timeline_events])->render();
                $events_count = $timeline_events->count();

            } else {

                $events_html = 'No events';
                $events_count = 0;

            }

            return response()->json(array(
                'success' => true,
                'events_html' => $events_html,
                'events_count' => $events_count
            ));

        }

    }

    public function like(Timeline $timeline, Request $request) 
    {

        if ($request->ajax()){

            if (auth()->check()) {

                // check if already liked
                if ($timeline->likedByUser()) {

                    $timeline->likes()->where('user_id', auth()->id())->where('timeline_id', $timeline->id)->delete();
                    $like = false;

                } else {

                    Like::create(['timeline_id' => $timeline->id, 'user_id' => auth()->id()]);
                    $like = true;
                    
                }

                return response()->json(array(
                    'success' => true,
                    'like' => $like
                ));

            } else {

                // show modal
                return response()->json(array(
                    'success' => false
                ));

            }

        }

    }

    public function save(Timeline $timeline) 
    {

        

    }

    public function tags($timeline_id) 
    {

        $timeline_tags = Timeline::find($timeline_id)->tags;
        //dd($timeline_tags);

        return view('layouts.timeline.ajax.tags', ['timeline_tags' => $timeline_tags]);

    }

}