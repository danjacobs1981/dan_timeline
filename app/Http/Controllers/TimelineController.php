<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Tag;
use App\Models\Share;

use Config;

class TimelineController extends Controller
{
    public function show(Timeline $timeline, Request $request) 
    {

        //updateTimeline($timeline->id);

        // set head items
        Config::set('constants.head.title', 'Timeline: '.$timeline->title);
        Config::set('constants.head.link_canonical', '/'.$timeline->id.'/'.$timeline->slug);

        if ($request->query('share')) {
            // dd($request->query('share'));
            // join the share table to the timeline table?
            // this will also add the class "event-start" if an initial event has been selected
        }
        
        return view('layouts.timeline.pages.timeline', ['timeline' => $timeline, 'temp_map' => 1, 'temp_comments' => 1, 'temp_filters' => 1, 'temp_tags' => 1]);

    }

    public function events(Timeline $timeline, Request $request) 
    {

        

        /*
        // get event ids belonging to tag
        //$tag_events = Tag::find(12345)->events->where('timeline_id', $timeline_id)->pluck('id')->toArray();
        $tag_events = Tag::with('events')->findMany([12345,67890])->where('timeline_id', $timeline_id)->pluck('id')->toArray();
        dd($tag_events);
        */

        /*
        // get tags belonging to event
        $event_tags = Event::find(72117)->tags->where('timeline_id', $timeline_id)->toArray();
        dd($event_tags);
        */
        

        // if share_id etc

        

        /*if ($selectedTags) {
            $tags = Tag::whereIn('id', $selectedTags)->where('timeline_id', $timeline_id)->get();
            if($tags) {
                $events = $tags->events;
                dd($events);
                $events_ids = array();
                foreach ($events as $event) {
                    array_push($events_ids, $event->id);
                }      
                $timeline_events = Timeline::find($timeline_id)->events->whereIn('id', $events_ids);           
            } else {
                $timeline_events = Timeline::find($timeline_id)->events;
            }
        } else {
            $timeline_events = Timeline::find($timeline_id)->events;
        }*/
        
        $selectedTags = array();

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
        }

        if ($timeline_events->count()) {

            //$event_first = $timeline_events->sortBy('order_ny')->first()->id;
            $events_html = view('layouts.timeline.ajax.events', ['timeline_events' => $timeline_events])->render();
            $events_count = $timeline_events->count();

        } else {

            $events_html = 'No results';
            $events_count = 0;

        }

        return response()->json(array(
            'success' => true,
            'events_html' => $events_html,
            'events_count' => $events_count
        ));

        
        /*$array = [];
        $counter = 1;

        // create JSON
        foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events) {
            foreach ($events->unique('order_ny') as $event) {
                if($event->date_year === null) {
                    $array[$counter++] = $event->toArray();
                } else {

                    $array[$event->date_year] = [];

                    foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event) {

                        

                        if($event->date_month === null) {

                            $array[$event->date_year] = $event->toArray();
                            
                        } else {
                            foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event) {

                                

                                if($event->date_day === null) {
                                    $array[$event->date_year][$event->date_month] = $event->toArray();
                                } else {
                                    foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event) {

                                        
                                        
                                        if($event->date_time === null) {
                                            $array[$event->date_year][$event->date_month][$event->date_day] = $event->toArray();
                                        } else {
                                            $array[$event->date_year][$event->date_month][$event->date_day][$event->date_unix_gmt] = $event->toArray();
                                        }
                                    }
                                }
                            }
                        }


                    }
                }
            }
        }

        //dd(json_encode($array));

        return response()->json($array);*/

        

    }

    public function tags($timeline_id) 
    {

        $timeline_tags = Timeline::find($timeline_id)->tags;
        //dd($timeline_tags);

        return view('layouts.timeline.ajax.tags', ['timeline_tags' => $timeline_tags]);

    }


}