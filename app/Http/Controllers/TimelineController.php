<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Source;
use App\Models\Tag;
use App\Models\Share;
use App\Models\Suggestion;
use App\Models\Report;
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

    public function showModalSuggestion(Timeline $timeline, Event $event)
    {

        if (auth()->check()) {

            $modal_title = 'Suggest Edit';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Submit Suggestion');
            $route = 'layouts.timeline.snippets.modal.suggestion';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'event'));

        } else {

            $show = 'login';
            $modal_title = 'Log In or Register';
            $incentive = 'You must log in to suggest edits...';
            $route = 'layouts.global.snippets.modal.login-register';
            return view('layouts.modal.master', compact('show', 'modal_title', 'incentive', 'route'));

        }

    }

    public function showModalReport(Timeline $timeline, Event $event)
    {

        $modal_title = 'Report Timeline';
        $modal_buttons = array('close' => 'Cancel', 'action' => 'Report');
        $route = 'layouts.timeline.snippets.modal.report';
        return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'event'));

    }

    public function suggestion(Timeline $timeline, Event $event, Request $request) 
    {

        if ($request->ajax()){

            if (auth()->check()) {

                if ($timeline) {

                    $data = $request->validate(
                        [
                            'anonymous' => 'boolean',
                            'comments' => [
                                'required',
                                'string',
                                'max:1000'
                            ]
                        ],
                        $messages = [
                            'comments.max' => 'Your suggestion must be 1000 characters or less',
                        ]
                    );

                    $data['timeline_id'] = $timeline->id;
                    $data['event_id'] = $event->id;
                    $data['user_id'] = auth()->user()->id;

                    Suggestion::create($data);

                    return response()->json(array(
                        'success' => true
                    ));

                }

            } else {

                // show modal
                return response()->json(array(
                    'success' => false
                ));

            }

        }

    }

    public function report(Timeline $timeline, Event $event, Request $request) 
    {

        if ($request->ajax()){

            if ($timeline) {

                $data = $request->validate(
                    [
                        'category' => 'string',
                        'comments' => [
                            'nullable',
                            'string',
                            'max:250'
                        ]
                    ],
                    $messages = [
                        'comments.max' => 'Your comments must be 1000 characters or less',
                    ]
                );

                $data['timeline_id'] = $timeline->id;
                $data['event_id'] = $event->id;

                if (auth()->check()) {
                    $data['user_id'] = auth()->user()->id;
                }

                Report::create($data);

                return response()->json(array(
                    'success' => true,
                    'message' => 'Report submitted'
                ));

            }

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