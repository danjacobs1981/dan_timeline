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
                    if ($request->date_day && $request->date_month && $request->date_year && $request->date_time) {
                        $request['date_unix'] = $request->date_day.'-'.$request->date_month.'-'.$request->date_year.' '.$request->date_time.' '.$request->date_time_ampm;
                        $date_type = 4;
                        //dd(Carbon::parse($request->date_day.'-'.$request->date_month.'-'.$request->date_year)->format('l'));
                    } else if ($request->date_day && $request->date_month && $request->date_year && !$request->date_time) {
                        $request['date_unix'] = $request->date_day.'-'.$request->date_month.'-'.$request->date_year.' 00:00:00';
                        $request['date_time'] = null;
                        $date_type = 3;
                    } else if ($request->date_month && $request->date_year && !$request->date_day && !$request->date_time) {
                        $request['date_unix'] = '01-'.$request->date_month.'-'.$request->date_year.' 00:00:00';
                        $request['date_day'] = null;
                        $request['date_time'] = null;
                        $date_type = 2;
                    } else if ($request->date_year && !$request->date_month && !$request->date_day && !$request->date_time) {
                        $request['date_unix'] = '01-01-'.$request->date_year.' 00:00:00';
                        $request['date_month'] = null;
                        $request['date_day'] = null;
                        $request['date_time'] = null;
                        $date_type = 1;
                    } else {
                        $request['date_time'] = null;
                        $request['date_unix'] = 0; // needs to be zero to throw validation error
                    }
                }

                $data = $request->validate(
                    [
                        'title' => 'required|max:255',
                        'date_year' => 'nullable|integer|digits:4',
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
                    $location = helperCurl('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['location_lat'].'%2C'.$data['location_lng'].'&key=AIzaSyCSqm7naEawzdea5d--gYQILDR28dxeL2Y'); // unrestricted key
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
                        $timezone = helperCurl('https://maps.googleapis.com/maps/api/timezone/json?location='.$data['location_lat'].'%2C'.$data['location_lng'].'&timestamp='.$data['date_unix'].'&key=AIzaSyCSqm7naEawzdea5d--gYQILDR28dxeL2Y'); // unrestricted key
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
                //dd($events);
                
                if($date_type == 1) {

                    // see if year exists and if so get its latest order_ym
                    $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first(); // does year already exist, and if so get ny & ym
                    //dd($exists_year);
                    if ($exists_year === null) {
                        $latest_moment = $events->whereNotNull('date_year')->max('date_year');
                        reorder($timeline_id, $events, $data, $latest_moment, 'date_year', 'order_ny');
                        $data['order_ym'] = 1; // its the first of this year to set ym as 1
                    } else {
                        $data['order_ny'] = $exists_year['order_ny']; 
                        $data['order_ym'] = $exists_year['order_ym'] + 1;
                    }

                } else if ($date_type == 2) {
                    
                    // see if year exists and if so get its latest order_ym
                    $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
                    if ($exists_year === null) {
                        $latest_moment = $events->whereNotNull('date_year')->max('date_year');
                        reorder($timeline_id, $events, $data, $latest_moment, 'date_year', 'order_ny');     
                        $data['order_ym'] = 1;
                        $data['order_md'] = 1;
                    } else {
                        $data['order_ny'] = $exists_year['order_ny']; 
                        // see if month exists and if so get its latest order_md
                        $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
                        if ($exists_month === null) {
                            $latest_moment = $events->where('date_year', $data['date_year'])->max('date_month');
                            reorder($timeline_id, $events, $data, $latest_moment, 'date_month', 'order_ym');     
                            $data['order_md'] = 1;
                        } else {
                            $data['order_ym'] = $exists_month['order_ym']; 
                            $data['order_md'] = $exists_month['order_md'] + 1; 
                        }
                    }

                } else if ($date_type == 3) {

                    // see if year exists and if so get its latest order_ym
                    $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
                    if ($exists_year === null) {
                        $latest_moment = $events->whereNotNull('date_year')->max('date_year');
                        reorder($timeline_id, $events, $data, $latest_moment, 'date_year', 'order_ny');
                        $data['order_ym'] = 1;
                        $data['order_md'] = 1;
                    } else {
                        $data['order_ny'] = $exists_year['order_ny']; 
                        // see if month exists and if so get its latest order_md
                        $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
                        if ($exists_month === null) {
                            $latest_moment = $events->where('date_year', $data['date_year'])->max('date_month');
                            reorder($timeline_id, $events, $data, $latest_moment, 'date_month', 'order_ym');  
                            $data['order_md'] = 1;
                        } else {
                            $data['order_ym'] = $exists_month['order_ym']; 
                            // see if day exists and if so get its latest order_dt
                            $exists_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->sortByDesc('order_dt')->first();
                            if ($exists_day === null) {
                                $latest_moment = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('date_day');
                                reorder($timeline_id, $events, $data, $latest_moment, 'date_day', 'order_md');   
                                $data['order_dt'] = 1;
                            } else {
                                $data['order_md'] = $exists_day['order_md']; 
                                $data['order_dt'] = $exists_day['order_dt'] + 1; 
                            }
                        }
                    }

                } else if ($date_type == 4) {

                    // see if year exists and if so get its latest order_ym
                    $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
                    if ($exists_year === null) {
                        $latest_moment = $events->whereNotNull('date_year')->max('date_year');
                        reorder($timeline_id, $events, $data, $latest_moment, 'date_year', 'order_ny'); 
                        $data['order_ym'] = 1;
                        $data['order_md'] = 1;
                        $data['order_dt'] = 1;
                    } else {
                        $data['order_ny'] = $exists_year['order_ny']; 
                        // see if month exists and if so get its latest order_md
                        $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
                        if ($exists_month === null) {
                            $latest_moment = $events->where('date_year', $data['date_year'])->max('date_month');
                            reorder($timeline_id, $events, $data, $latest_moment, 'date_month', 'order_ym');  
                            $data['order_md'] = 1;
                            $data['order_dt'] = 1;
                        } else {
                            $data['order_ym'] = $exists_month['order_ym']; 
                            // see if day exists and if so get its latest order_dt
                            $exists_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->sortByDesc('order_dt')->first();
                            if ($exists_day === null) {
                                $latest_moment = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('date_day');
                                reorder($timeline_id, $events, $data, $latest_moment, 'date_day', 'order_md');     
                                $data['order_dt'] = 1;
                            } else {
                                $data['order_md'] = $exists_day['order_md']; 
                                // sort the position based on its time
                                $latest_moment = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->max('date_unix_gmt');
                                if ($latest_moment <= $data['date_unix_gmt']) { 
                                    // new time is greater so add to very end of current order
                                    $data['order_dt'] = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->max('order_dt') + 1;
                                } else { 
                                    // new time is less so find it's next_moment
                                    $next_moment = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->where('date_unix_gmt','>', $data['date_unix_gmt'])->sortBy('date_unix_gmt')->first();
                                    // increment order for that time / all items ordered higher
                                    Event::where('timeline_id', $timeline_id)->where('date_year', $next_moment['date_year'])->where('date_month', $next_moment['date_month'])->where('date_day', $next_moment['date_day'])->where('order_dt','>=', $next_moment['order_dt'])->increment('order_dt');
                                    // apply order to current time (one less than next_moment)
                                    $data['order_dt'] = $next_moment['order_dt']; 
                                }      
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
                $show = Event::create($data);
                //dd($show);

                // set order_overall for all events
                //updateTimeline($timeline_id);


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

function reorder($timeline_id, $events, &$data, $latest_moment, $data_date, $data_order) {
    if ($latest_moment <= $data[$data_date]) { // event happens after
        $data[$data_order] = $events->max($data_order) + 1; // set's this order_* as the latest (+ 1)
    } else { // event happens before
        $next_moment = $events->whereNotNull($data_date)->where($data_date,'>', $data[$data_date])->sortBy($data_date)->first(); // find the very next moment and get its order_* and date_*
        Event::where('timeline_id', $timeline_id)->where($data_order,'>=', $next_moment[$data_order])->increment($data_order); // updates the next_moment and all that come after order_* to + 1
        $data[$data_order] = $next_moment[$data_order]; // set's this order_* as to what the next_moment was previously
    }
}