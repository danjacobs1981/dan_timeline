<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Source;
use App\Models\Tag;
use App\Models\Share;
use App\Models\Like;
use App\Models\Save;

//use App\Mail\AmazonSESMail;
//use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use Config;

class TimelineController extends Controller
{
    
    public function show(Timeline $timeline, Request $request) 
    {

        // Mail::to('success@simulator.amazonses.com')->send(new AmazonSESMail());

        if ($timeline->privacy > 1 || checkCanViewTimeline($timeline->user_id, $timeline->id)) { 

            $date = $timeline->events->sortBy('order_overall')->value('date_unix');
            $start_date = Carbon::createFromTimestamp($timeline->events->sortBy('order_overall')->value('date_unix'))->format('jS F Y');
            $end_date = Carbon::createFromTimestamp($timeline->events->sortByDesc('order_overall')->value('date_unix'))->format('jS F Y');

            $summary = 'A visual timeline of events';

            if ($date) {
                if($start_date === $end_date) {
                    $summary = 'A visual timeline of events that happened on the '.$start_date;
                } else {
                    $summary = 'A visual timeline of events that spanned between the '.$start_date.' and the '.$end_date;
                }
            }

            // set head items
            Config::set('constants.head.title', 'Timeline: '.$timeline->title.' | A Visual Timeline of Events');
            Config::set('constants.head.meta_title', 'Timeline: '.$timeline->title.' | A Visual Timeline of Events');
            Config::set('constants.head.meta_description', $summary.' - '.$timeline->title.'.');
            Config::set('constants.head.link_canonical', config('constants.website.url_full').'/'.$timeline->id.'/'.$timeline->slug);

            if ($request->query('share')) {
                // dd($request->query('share'));
                // join the share table to the timeline table?
                // this will also add the class "event-start" if an initial event has been selected
            }

            //dd($timeline->eventsTaggedIDs->makeHidden('laravel_through_key')->toArray());

            if ($timeline->tagging) {
                $tags = $timeline->tagsUsedGroups;
            } else {
                $tags = $timeline->tagsUsed;
            }
            
            //dd($tags);

            return view('layouts.timeline.pages.timeline', [ 'timeline' => $timeline, 'tags' => $tags, 'summary' => $summary ]);

        } else {

            // error "private timeline" page
            return view('errors.private');

        }

    }

    public function events(Timeline $timeline, Request $request) 
    {

        if ($request->ajax()){

            $events_markers = [];

            if ($request->tags) {
                $timeline_events = $timeline->eventsByTag($request->tags)->get();
            } else {
                $timeline_events = $timeline->events;
            }

            //dd($timeline_events);

            if ($timeline_events->count()) {

                $events_markers = $timeline_events->where('location_show', 1)->map->only([ 'title',  'period',  'location', 'location_lat', 'location_lng', 'location_zoom', 'order_overall' ])->sortBy('order_overall')->values()->all();
                $events_html = view('layouts.timeline.ajax.events', ['timeline_events' => $timeline_events])->render();
                $events_count = $timeline_events->count();

            } else {

                $events_html = 'No events';
                $events_count = 0;

            }

            return response()->json(array(
                'success' => true,
                'events_markers' => $events_markers,
                'events_html' => $events_html,
                'events_count' => $events_count
            ));

        }

    }

    public function showModalEdit(Timeline $timeline)
    {

        $modal_title = 'Suggest an edit';
        $modal_buttons = array('close' => 'Cancel', 'action' => 'Suggest Edit', 'form' => 'formEdit');
        $route = 'layouts.timeline.snippets.modal.edit';
        return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline'));

    }

    public function showModalReport(Timeline $timeline)
    {

        $modal_title = 'Report Timeline';
        $modal_buttons = array('close' => 'Cancel', 'action' => 'Report', 'form' => 'formReport');
        $route = 'layouts.timeline.snippets.modal.report';
        return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline'));

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
                    'increment' => $like,
                    'count' => convert($timeline->likesCount()),
                ));

            } else {

                // show modal
                return response()->json(array(
                    'success' => false
                ));

            }

        }

    }

    public function save(Timeline $timeline, Request $request) 
    {

        if ($request->ajax()){

            if (auth()->check()) {

                // check if already saved
                if ($timeline->savedByUser()) {

                    $timeline->saves()->where('user_id', auth()->id())->where('timeline_id', $timeline->id)->delete();
                    $save = false;

                } else {

                    Save::create(['timeline_id' => $timeline->id, 'user_id' => auth()->id()]);
                    $save = true;
                    
                }

                return response()->json(array(
                    'success' => true,
                    'increment' => $save,
                ));

            } else {

                // show modal
                return response()->json(array(
                    'success' => false
                ));

            }

        }

    }

    /*public function tags($timeline_id) 
    {

        $timeline_tags = Timeline::find($timeline_id)->tags;
        //dd($timeline_tags);

        return view('layouts.timeline.ajax.tags', ['timeline_tags' => $timeline_tags]);

    }*/

}

function convert($n) {
    if ($n < 1000) {
        $n_format = number_format($n);
    } else if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n / 1000, 3) . 'k';
    } else {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, 3) . 'M';
    }
    return $n_format;
}