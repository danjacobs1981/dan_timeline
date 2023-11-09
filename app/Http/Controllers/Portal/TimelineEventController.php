<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Timeline;
use App\Models\Event;
use Carbon\Carbon;


class TimelineEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Timeline $timeline)
    {

        $timeline_events = $timeline->events;

        if ($timeline_events->count()) {

            $events_html = view('layouts.portal.ajax.timeline.events', ['timeline_events' => $timeline_events])->render();
            $events_count = $timeline_events->count().' events in timeline';

        } else {

            $events_html = null;
            $events_count = 'Add an event to get started!';

        }

        return response()->json(array(
            'success' => true,
            'events_html' => $events_html,
            'events_count' => $events_count
        ));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Timeline $timeline, Request $request)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $predate = $request->predate;

            $modal_title = 'Add Event';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Add Event', 'form' => 'formEventCreateEdit');
            $route = 'layouts.portal.pages.timeline.event.create-edit';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'predate'));

        } else {

            abort(401);

        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Timeline $timeline, Request $request)
    {

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                $date_type = getDateType($request);

                $data = $request->validate(
                    [
                        'title' => 'required|string|max:255',
                        'date_year' => 'nullable|integer|digits:4|max:9999',
                        'date_month' => 'nullable|numeric|between:1,12',
                        'date_day' => 'nullable|numeric|between:1,31',
                        'date_time' => 'nullable|date_format:h:i',
                        'date_unix' => 'nullable|date',
                        'location_lat' => 'nullable',
                        'location_lng' => 'nullable',
                        'location_geo_street' => 'boolean',
                        'location_show' => 'boolean',
                    ],
                    [   
                        'title.required' => 'Please enter a title',
                        'title.max' => 'Please enter a title (max 255 chars)',
                        'date_unix.date' => 'Please enter a valid date'
                    ]
                );

                // get geo location details
                if ($data['location_lat'] && $data['location_lng']) {
                    $location = helperCurl('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['location_lat'].'%2C'.$data['location_lng'].'&key='.env('VITE_GOOGLE_API'));
                    if ($location->status == 'OK') {
                        $street = '';
                        $city = '';
                        $country = '';
                        // street
                        foreach ($location->results as $result) {
                            foreach ($result->address_components as $address) {
                                if (in_array('route', $address->types)) {
                                    $street = $address->long_name;
                                }
                            }
                        }
                        // city
                        foreach ($location->results as $result) {
                            foreach ($result->address_components as $address) {
                                if (in_array('locality', $address->types)) {
                                    $city = $address->long_name;
                                }
                            }
                        }
                        // country
                        foreach ($location->results as $result) {
                            foreach ($result->address_components as $address) {
                                if (in_array('country', $address->types)) {
                                    $country = $address->long_name;
                                }
                            }
                        }
                        $location_geo = '';
                        if (($data['location_geo_street'] && ($street != '' && $street != 'Unnamed Road')) && $city != '' && $country != '') {
                            $location_geo = $street.', '.$city.', '.$country;
                        } else if ($city != '' && $country != '') {
                            $location_geo = $city.', '.$country;
                        } else if ($country != '') {
                            $location_geo = $country;
                        }
                        if ($location_geo != '') {
                            $data['location_geo'] = $location_geo;
                        }
                    }
                }

                // get time details
                if ($data['date_unix']) {
                    $data['date_unix'] = Carbon::parse($data['date_unix'])->timestamp;
                    $data['date_unix_gmt'] = $data['date_unix'];
                    if ($data['date_time']) {
                        $data['date_time'] = Carbon::parse($request->date_time.' '.$request->date_time_ampm)->format('H:i'); // converts to 24 hour
                        $data['location_tz'] = 'Coordinated Universal Time'; // time exists so set UTC as default timezone
                    }
                    if ($data['date_time'] && $data['location_lat'] && $data['location_lng']) {
                        $timezone = helperCurl('https://maps.googleapis.com/maps/api/timezone/json?location='.$data['location_lat'].'%2C'.$data['location_lng'].'&timestamp='.$data['date_unix'].'&key='.env('VITE_GOOGLE_API'));
                        if ($timezone->status == 'OK') {
                            $data['date_unix_gmt'] = $data['date_unix'] + (($timezone->dstOffset + $timezone->rawOffset) * -1);
                            $data['location_tz'] = $timezone->timeZoneId; // and then overrides timezone if successful
                        } else {
                            $data['location_tz_error'] = 1; 
                        }
                    }
                } else {
                    $data['date_month'] = null;
                    $data['date_day'] = null;
                    $data['date_time'] = null;
                }

                // reorder items during its section & period

                $events = $timeline->events;
                
                if ($date_type == 1) {

                    // YEAR
                    $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
                    if ($exists_year === null) {
                        $data['order_ny'] = reorderYear($timeline_id, $events, $data);
                        $data['order_ym'] = 1;
                    } else {
                        $data['order_ny'] = $exists_year['order_ny'];
                        $data['order_ym'] = $exists_year['order_ym'] + 1;
                    }

                } else if ($date_type == 2) {
                    
                    // YEAR
                    $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
                    if ($exists_year === null) {
                        $data['order_ny'] = reorderYear($timeline_id, $events, $data);
                        $data['order_ym'] = 1;
                        $data['order_md'] = 1;
                    } else {
                        // MONTH
                        $data['order_ny'] = $exists_year['order_ny']; 
                        $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
                        if ($exists_month === null) {
                            $data['order_ym'] = reorderMonth($timeline_id, $events, $data);
                            $data['order_md'] = 1;
                        } else {
                            $data['order_ym'] = $exists_month['order_ym']; 
                            $data['order_md'] = $exists_month['order_md'] + 1; 
                        }
                    }

                } else if ($date_type == 3) {

                    // YEAR
                    $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
                    if ($exists_year === null) {
                        $data['order_ny'] = reorderYear($timeline_id, $events, $data);
                        $data['order_ym'] = 1;
                        $data['order_md'] = 1;
                        $data['order_dt'] = 1;
                    } else {
                        // MONTH
                        $data['order_ny'] = $exists_year['order_ny']; 
                        $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
                        if ($exists_month === null) {
                            $data['order_ym'] = reorderMonth($timeline_id, $events, $data);
                            $data['order_md'] = 1;
                            $data['order_dt'] = 1;
                        } else {
                            // DAY
                            $data['order_ym'] = $exists_month['order_ym']; 
                            $exists_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->sortByDesc('order_dt')->first();
                            if ($exists_day === null) {
                                $data['order_md'] = reorderDay($timeline_id, $events, $data);
                                $data['order_dt'] = 1;
                            } else {
                                $data['order_md'] = $exists_day['order_md']; 
                                $data['order_dt'] = $exists_day['order_dt'] + 1; 
                            }
                        }
                    }

                } else if ($date_type == 4) {

                    // YEAR
                    $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
                    if ($exists_year === null) {
                        $data['order_ny'] = reorderYear($timeline_id, $events, $data);
                        $data['order_ym'] = 1;
                        $data['order_md'] = 1;
                        $data['order_dt'] = 1;
                    } else {
                        // MONTH
                        $data['order_ny'] = $exists_year['order_ny']; 
                        $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
                        if ($exists_month === null) {
                            $data['order_ym'] = reorderMonth($timeline_id, $events, $data);
                            $data['order_md'] = 1;
                            $data['order_dt'] = 1;
                        } else {
                            // DAY
                            $data['order_ym'] = $exists_month['order_ym']; 
                            $exists_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->sortByDesc('order_dt')->first();
                            if ($exists_day === null) {
                                $data['order_md'] = reorderDay($timeline_id, $events, $data);
                                $data['order_dt'] = 1;
                            } else {
                                // TIME
                                $data['order_md'] = $exists_day['order_md']; 
                                $data['order_dt'] = reorderTime($timeline_id, $events, $data);    
                            }
                        }
                    }

                } else {

                    $data['order_ny'] = $events->max('order_ny') + 1;
                    
                }

                // other data
                $data['date_type'] = $date_type;
                $data['id'] = helperUniqueId('events');
                $data['timeline_id'] = $timeline_id;
                
                //dd($data);

                // add the event
                Event::create($data);

                return response()->json([
                    'status'=> 200,
                    'message' => 'Event created successfully',
                    'loadEvents' => true,
                    'timeline_id' => $timeline_id
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
     * Display the specified resource.
     */
    public function show(string $id): never
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timeline $timeline, Event $event)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Edit Event';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Update Event', 'form' => 'formEventCreateEdit');
            $route = 'layouts.portal.pages.timeline.event.create-edit';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'event'));

        } else {

            abort(401);

        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Timeline $timeline, Event $event, Request $request)
    {
        
        //dd($request);

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                $date_type = getDateType($request);

                if ($request->date) { // only updating date

                    $data = $request->validate(
                        [
                            'date_year' => 'nullable|integer|digits:4|max:9999',
                            'date_month' => 'nullable|numeric|between:1,12',
                            'date_day' => 'nullable|numeric|between:1,31',
                            'date_time' => 'nullable|date_format:h:i',
                            'date_unix' => 'nullable|date'
                        ],
                        [   
                            'date_unix.date' => 'Please enter a valid date'
                        ]
                    );

                    if ($data['date_unix']) {
                        $data['date_unix'] = Carbon::parse($data['date_unix'])->timestamp;
                    }

                    if ($data['date_unix'] != $event->date_unix) {

                        // date has been updated
                        dd("date has been updated");

                        return response()->json([
                            'status'=> 200,
                            'message' => 'Date updated',
                            'loadEvents' => true,
                            'timeline_id' => $timeline_id
                        ]);

                    } else {

                        return response()->json([
                            'status'=> 200,
                            'message' => 'No change in date',
                            'loadEvents' => false,
                            'timeline_id' => $timeline_id
                        ]);

                    }

                } else { // full event update

                    dd("full event update");

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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showModalDate(Timeline $timeline, Event $event)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Change Event Date';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Change Date', 'form' => 'formEventEditDate');
            $route = 'layouts.portal.snippets.edit-event-date';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'event'));

        } else {

            abort(401);

        }

    }

}

function getDateType($request) {
    $request['date_unix'] = null;
    if ($request->date_day || $request->date_month || $request->date_year || $request->date_time) {
        if ($request->date_day && $request->date_month && $request->date_year && $request->date_time) { // TIME
            $request['date_unix'] = $request->date_day.'-'.$request->date_month.'-'.$request->date_year.' '.$request->date_time.' '.$request->date_time_ampm;
            return 4;
        } else if ($request->date_day && $request->date_month && $request->date_year && !$request->date_time) { // DAY
            $request['date_unix'] = $request->date_day.'-'.$request->date_month.'-'.$request->date_year.' 00:00:00';
            $request['date_time'] = null;
            return 3;
        } else if ($request->date_month && $request->date_year && !$request->date_day && !$request->date_time) { // MONTH
            $request['date_unix'] = '01-'.$request->date_month.'-'.$request->date_year.' 00:00:00';
            $request['date_day'] = null;
            $request['date_time'] = null;
            return 2;
        } else if ($request->date_year && !$request->date_month && !$request->date_day && !$request->date_time) { // YEAR
            $request['date_unix'] = '01-01-'.$request->date_year.' 00:00:00';
            $request['date_month'] = null;
            $request['date_day'] = null;
            $request['date_time'] = null;
            return 1;
        }
    } else {
        return null; // NONE
    }
}

function reorderYear($timeline_id, $events, &$data) {
    $latest_year = $events->whereNotNull('date_year')->max('date_year');
    if ($data['date_year'] >= $latest_year) { // event happens after
        return $events->max('order_ny') + 1;
    } else { // event happens before
        $after_year = $events->whereNotNull('date_year')->where('date_year','>', $data['date_year'])->sortBy('date_year')->first();
        Event::where('timeline_id', $timeline_id)->where('order_ny','>=', $after_year['order_ny'])->increment('order_ny');
        return $after_year['order_ny'];
    }
}

function reorderMonth($timeline_id, $events, &$data) {
    $latest_month = $events->where('date_year', $data['date_year'])->whereNotNull('date_month')->max('date_month');
    if ($data['date_month'] >= $latest_month) { // event happens after
        return $events->where('date_year', $data['date_year'])->max('order_ym') + 1;
    } else { // event happens before
        $after_month = $events->where('date_year', $data['date_year'])->whereNotNull('date_month')->where('date_month','>', $data['date_month'])->sortBy('date_month')->first();
        Event::where('timeline_id', $timeline_id)->where('date_year', $data['date_year'])->where('order_ym','>=', $after_month['order_ym'])->increment('order_ym');
        return $after_month['order_ym'];
    }
}

function reorderDay($timeline_id, $events, &$data) {
    $latest_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->whereNotNull('date_day')->max('date_day');
    if ($data['date_day'] >= $latest_day) { // event happens after
        return $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('order_md') + 1;
    } else { // event happens before
        $after_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->whereNotNull('date_day')->where('date_day','>', $data['date_day'])->sortBy('date_day')->first();
        Event::where('timeline_id', $timeline_id)->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('order_md','>=', $after_day['order_md'])->increment('order_md');
        return $after_day['order_md'];
    }
}

function reorderTime($timeline_id, $events, &$data) {
    $latest_time = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->whereNotNull('date_time')->max('date_unix_gmt');
    if ($latest_time <= $data['date_unix_gmt']) { 
        return $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->max('order_dt') + 1;
    } else { 
        $after_time = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->whereNotNull('date_time')->where('date_unix_gmt','>', $data['date_unix_gmt'])->sortBy('date_unix_gmt')->first();
        Event::where('timeline_id', $timeline_id)->where('date_year', $after_time['date_year'])->where('date_month', $after_time['date_month'])->where('date_day', $after_time['date_day'])->where('order_dt','>=', $after_time['order_dt'])->increment('order_dt');
        return $after_time['order_dt']; 
    }  
}