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

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Timeline $timeline)
    {
        return view('layouts.portal.pages.timeline.event.create', compact('timeline'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Timeline $timeline, Request $request)
    {

        if($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $date_type = null;

                $request['date_unix'] = null;

                if ($request->date_day || $request->date_month || $request->date_year || $request->date_time) {
                    if ($request->date_day && $request->date_month && $request->date_year && $request->date_time) { // TIME
                        $request['date_unix'] = $request->date_day.'-'.$request->date_month.'-'.$request->date_year.' '.$request->date_time.' '.$request->date_time_ampm;
                        $date_type = 4;
                    } else if ($request->date_day && $request->date_month && $request->date_year && !$request->date_time) { // DAY
                        $request['date_unix'] = $request->date_day.'-'.$request->date_month.'-'.$request->date_year.' 00:00:00';
                        $request['date_time'] = null;
                        $date_type = 3;
                    } else if ($request->date_month && $request->date_year && !$request->date_day && !$request->date_time) { // MONTH
                        $request['date_unix'] = '01-'.$request->date_month.'-'.$request->date_year.' 00:00:00';
                        $request['date_day'] = null;
                        $request['date_time'] = null;
                        $date_type = 2;
                    } else if ($request->date_year && !$request->date_month && !$request->date_day && !$request->date_time) { // YEAR
                        $request['date_unix'] = '01-01-'.$request->date_year.' 00:00:00';
                        $request['date_month'] = null;
                        $request['date_day'] = null;
                        $request['date_time'] = null;
                        $date_type = 1;
                    } else { // NONE
                        $request['date_unix'] = 0; // needs to be zero to throw validation error
                        $request['date_time'] = null;
                    }
                }

                $data = $request->validate(
                    [
                        'title' => 'required|string|max:255',
                        'date_year' => 'nullable|integer|digits:4|max:9999',
                        'date_month' => 'nullable|numeric|between:1,12',
                        'date_day' => 'nullable|numeric|between:1,31',
                        'date_time' => 'nullable|date_format:h:i',
                        //'date_time_ampm' => 'nullable|in:AM,PM',
                        'date_unix' => 'nullable|date',
                        'location_lat' => 'required_with:date_time',
                        'location_lng' => 'required_with:date_time',
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
                    $data['date_unix'] = Carbon::parse($request->date_unix)->timestamp;
                    $data['date_unix_gmt'] = null;
                    if ($data['date_time']) {
                        $data['date_time'] = Carbon::parse($request->date_time.' '.$request->date_time_ampm)->format('H:i'); // converts to 24 hour
                    }
                    if ($data['date_time'] && $data['location_lat'] && $data['location_lng']) {
                        $timezone = helperCurl('https://maps.googleapis.com/maps/api/timezone/json?location='.$data['location_lat'].'%2C'.$data['location_lng'].'&timestamp='.$data['date_unix'].'&key='.env('VITE_GOOGLE_API'));
                        if ($timezone->status == 'OK') {
                            $data['date_unix_gmt'] = $data['date_unix'] + (($timezone->dstOffset + $timezone->rawOffset) * -1);
                            $data['location_tz'] = $timezone->timeZoneId;
                            $data['location_tz_error'] = 0; 
                        } else {
                            $data['location_tz_error'] = 1; 
                            $data['location_tz'] = 'Coordinated Universal Time';
                        }
                    }
                } else {
                    $data['date_month'] = null;
                    $data['date_day'] = null;
                    $data['date_time'] = null;
                }

                // reorder items during its section & period

                $timeline_id = $timeline->id;
                $events = $timeline->events;
                
                if($date_type == 1) {

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): never
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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