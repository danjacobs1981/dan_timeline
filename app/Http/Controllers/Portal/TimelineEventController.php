<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Source;
use Carbon\Carbon;

use Config;
use Image;

class TimelineEventController extends Controller
{

    public function __construct()
    {


    }

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
                        'title' => 'required|string|max:250',
                        'date_year' => 'nullable|integer|digits:4|max:9999',
                        'date_month' => 'nullable|numeric|between:1,12',
                        'date_day' => 'nullable|numeric|between:1,31',
                        'date_time' => 'nullable|date_format:h:i',
                        'date_unix' => 'nullable|date',
                        'location_lat' => 'nullable',
                        'location_lng' => 'nullable',
                        'location_show' => 'integer|between:0,2',
                        'location_geo' => 'nullable|integer|between:1,5',
                        'location_zoom' => 'nullable|integer|between:3,19',
                        'location_tz' => 'nullable|string',
                        'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:4096',
                        'image_thumbnail' => 'nullable',
                        'image_large' => 'nullable',
                        'description' => 'nullable|max:1000',
            
                    ],
                    [   
                        'title.required' => 'The event requires a title',
                        'title.max' => 'The title must be 250 characters or less',
                        'date_unix.date' => 'The date must be valid'
                ]
                );

                // get time details
                if ($data['date_unix']) {
                    $data['date_unix'] = Carbon::parse($data['date_unix'])->timestamp;
                    $data['date_unix_gmt'] = $data['date_unix'];
                    if ($data['date_time']) {
                        $data['date_time'] = Carbon::parse($data['date_time'].' '.$request->date_time_ampm)->format('H:i'); // converts to 24 hour
                        $data['location_tz'] = 'Coordinated Universal Time'; // time exists so set UTC as default timezone
                    }
                } /*else {
                    $data['date_month'] = null;
                    $data['date_day'] = null;
                    $data['date_time'] = null;
                }*/

                // get geo location details
                if ($data['location_show'] && $data['location_lat'] && $data['location_lng']) {
                    // if a time is set then get a timezone
                    if ($data['date_time']) {
                        getTimezone($data);
                    }         
                    // want geocode details        
                    if ($data['location_show'] == 1) {
                        getGeo($data);
                    }
                }

                $events = $timeline->events;

                // reorder items during its section & period
                reorderAll($date_type, $timeline_id, $events, $data);

                // image
                $image_name = null;
                if ($request->hasFile('image')) {
                    $image = $request->image;
                    $image_name = time().'_'.$image->getClientOriginalName();
                }
                $data['image'] = $image_name;

                // other data
                $data['description'] = strip_tags($data['description']);
                $data['date_type'] = $date_type;
                $data['id'] = helperUniqueId('events');
                $data['timeline_id'] = $timeline_id;

                // resize & move image
                if ($image_name) {
                    $path = 'public/images/timeline/'.$timeline_id.'/'.$data["id"].'/';
                    $file_extension = $image->getClientOriginalExtension();
                    $image_file = Image::make($image)->resize(800, 800, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode($file_extension);
                    Storage::put($path.$image_name, (string)$image_file, 'public');
                }

                // add the event
                $event = new Event;
                $event->create($data);

                // tags
                if ($request->tags_changed) {
                    // add new ones
                    //$event = new Event;
                    $event->tags()->attach($request->tags, ['event_id' => $data['id'], 'timeline_id' => $timeline_id]);
                }

                // sources
                if ($request->sources_changed) {
                    // add new ones
                    //$event = new Event;
                    $event->sources()->attach($request->sources, ['event_id' => $data['id'], 'timeline_id' => $timeline_id]);
                }

                return response()->json([
                    'status'=> 200,
                    'message' => 'Event created successfully',
                    'loadEvents' => true,
                    'timeline_id' => $timeline_id,
                    'event_id' => $data['id'],
                    'update' => false
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

            // get any sources that have been added
            //$sources = $event->sourcesIDs()->all();

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
        
        //dd($request->tags);

        if ($request->ajax()){
            
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline_id = $timeline->id;

                $date_type = getDateType($request);

                //dd($date_type);

                if ($request->date) { // only updating date

                    $data = $request->validate(
                        [
                            'date_year' => 'nullable|integer|digits:4|max:9999',
                            'date_month' => 'nullable|numeric|between:1,12',
                            'date_day' => 'nullable|numeric|between:1,31',
                            'date_time' => 'nullable|date_format:h:i',
                            'date_unix' => 'nullable|date',
                            'location_lat' => 'nullable',
                            'location_lng' => 'nullable',
                            'location_show' => 'integer|between:0,2',
                            'location_geo' => 'nullable|integer|between:1,5',
                            'location_zoom' => 'nullable|integer|between:3,19',
                            ],
                        [   
                            'date_unix.date' => 'The date must be valid'
                        ]
                    );

                    $dateChange = updateDateMap($date_type, $timeline, $data, $event, $request);

                    // other data
                    $data['date_type'] = $date_type;

                    // update the event
                    $event->update($data);
                    //Event::where('timeline_id', $timeline_id)->where('id', $event->id)->update($data);

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Event date updated successfully',
                        'loadEvents' => $dateChange,
                        'timeline_id' => $timeline_id,
                        'event_id' => $event->id,
                        'update' => true
                    ]);

                } else { // full event update

                    //dd($request);

                    $data = $request->validate(
                        [
                            'title' => 'required|string|max:250',
                            'date_year' => 'nullable|integer|digits:4|max:9999',
                            'date_month' => 'nullable|numeric|between:1,12',
                            'date_day' => 'nullable|numeric|between:1,31',
                            'date_time' => 'nullable|date_format:h:i',
                            'date_unix' => 'nullable|date',
                            'location_lat' => 'nullable',
                            'location_lng' => 'nullable',
                            'location_show' => 'integer|between:0,2',
                            'location_geo' => 'nullable|integer|between:1,5',
                            'location_zoom' => 'nullable|integer|between:3,19',
                            'location_tz' => 'nullable|string',
                            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:4096',
                            'image_thumbnail' => 'nullable',
                            'image_large' => 'nullable',
                            'description' => 'nullable|max:1000',
                
                        ],
                        [   
                            'title.required' => 'The event requires a title',
                            'title.max' => 'The title must be 250 characters or less',
                            'date_unix.date' => 'The date must be valid'
                        ]
                    );
                        
                    $dateChange = updateDateMap($date_type, $timeline, $data, $event, $request);

                    // tags
                    if ($request->tags_changed) {
                        // remove existing tags based on event id
                        $event->tags()->detach();
                        //dd(json_decode($request->tags));
                        foreach(json_decode($request->tags) as $tag) {
                            $event->tags()->attach($tag->id, ['timeline_id' => $timeline_id, 'highlight' => $tag->highlight]);
                        }
                    }

                    // sources
                    if ($request->sources_changed) {
                        // remove existing sources based on event id
                        $event->sources()->detach();
                        // add new ones - 'sync' removes any that aren't in the array
                        $event->sources()->attach($request->sources, ['timeline_id' => $timeline_id]);
                    }

                    // image
                    $image_delete = 0;
                    $data['image'] = $event->image;
                    if ($event->image && $request->image_delete) {
                        $image_delete = 1;
                        $data['image'] = null;
                    }
                    if ($request->hasFile('image')) {
                        $image = $request->image;
                        $data['image'] = time().'_'.$image->getClientOriginalName();
                        $path = 'public/images/timeline/'.$timeline_id.'/'.$event->id.'/';
                        $file_extension = $image->getClientOriginalExtension();
                        $image_file = Image::make($image)->resize(800, 800, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode($file_extension);
                        Storage::put($path.$data['image'], (string)$image_file, 'public');
                        $image_delete = 1;
                    }
                    // delete old image
                    if ($image_delete) {
                        $old_image_path = 'public/images/timeline/'.$timeline_id.'/'.$event->id.'/'.$event->image;
                        if (Storage::exists($old_image_path)) {
                            Storage::delete($old_image_path);
                        }
                    }

                    // other data
                    $data['description'] = strip_tags($data['description']);
                    $data['date_type'] = $date_type;

                    // update the event
                    $event->update($data);
                    //Event::where('timeline_id', $timeline_id)->where('id', $event->id)->update($data);

                    return response()->json([
                        'status'=> 200,
                        'message' => 'Event updated successfully',
                        'loadEvents' => $dateChange,
                        'timeline_id' => $timeline_id,
                        'event_id' => $event->id,
                        'event_title' => $data['title'],
                        'update' => true
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): never
    {
        abort(404);
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

    public function showModalLocation(Timeline $timeline, Event $event)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $modal_title = 'Event Location';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Done');
            $route = 'layouts.portal.snippets.edit-event-location';
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

function getTimezone(&$data) {
    $timezone_json = helperCurl('https://maps.googleapis.com/maps/api/timezone/json?location='.$data['location_lat'].'%2C'.$data['location_lng'].'&timestamp='.$data['date_unix'].'&key='.Config::get('app.google_api_key'));
    if ($timezone_json->status == 'OK') {
        $data['date_unix_gmt'] = $data['date_unix'] + (($timezone_json->dstOffset + $timezone_json->rawOffset) * -1);
        $data['location_tz'] = str_replace('_', ' ', $timezone_json->timeZoneId); // and then overrides timezone if successful
        $data['location_tz_error'] = 0; 
    } else {
        $data['location_tz_error'] = 1; 
    }
}

function getGeo(&$data) {
    $geocode_json = helperCurl('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['location_lat'].'%2C'.$data['location_lng'].'&key='.Config::get('app.google_api_key'));
    if ($geocode_json->status == 'OK') {
        $building = null;
        $street = null;
        $city = null;
        $region = null;
        $country = null;
        foreach ($geocode_json->results as $result) {
            foreach ($result->address_components as $address) {
                if (in_array('premise', $address->types)) {
                    $building = $address->long_name;
                }
                if (in_array('route', $address->types)) {
                    $street = $address->long_name;
                }
                if (in_array('locality', $address->types)) {
                    $city = $address->long_name;
                } else if (in_array('postal_town', $address->types)) {
                    $city = $address->long_name;
                } else if (in_array('administrative_area_level_3', $address->types)) {
                    $city = $address->long_name;
                }
                if (in_array('administrative_area_level_2', $address->types)) {
                    $region = $address->long_name;
                } else if (in_array('administrative_area_level_1', $address->types)) {
                    $region = $address->long_name;
                }
                if (in_array('country', $address->types)) {
                    $country = $address->long_name;
                }
            }
        }
        $data['location_geo_building'] = $building;
        $data['location_geo_street'] = $street;
        $data['location_geo_city'] = $city;
        $data['location_geo_region'] = $region;
        $data['location_geo_country'] = $country;
        // build up $data['location'] based on $data['location_geo'] value
        $data['location'] = setLocation($data['location_geo'], $building, $street, $city, $region, $country);
    }
}

function setLocation($location_geo, $building, $street, $city, $region, $country) {
    $location_first = null;
    $location_second = null;
    if ($location_geo == 1) {
        $location_first = firstNonEmpty([$building, $street]);
        if (!$location_first || $location_first == 'Unnamed Road') {
            $location_first = firstNonEmpty([$city, $region]);
        } else {
            $location_second = firstNonEmpty([$city, $region]);
        }
    } else if ($location_geo == 2) {
        $location_first = firstNonEmpty([$street, $city]);
        if (!$location_first || $location_first == 'Unnamed Road') {
            $location_first = $region;
        } else {
            $location_second = $region;
        }
    } else if ($location_geo == 3) {
        $location_first = firstNonEmpty([$city, $region]);
    } else if ($location_geo == 4) {
        $location_first = $region;
    }
    $location = null;
    if ($location_first && $location_second) {
        $location = $location_first.', '.$location_second;
    } else if ($location_first || $location_second) {
        $location = firstNonEmpty([$location_first, $location_second]);
    }
    if ($location && $country) {
        $location = $location.', '.$country;
    } else if ($country) {
        $location = $country;
    }
    //dd($location);
    return $location;
}

function updateDateMap($date_type, $timeline, &$data, $event, $request) {  
    $mapChangeOn = false;
    $mapChangeOff = false;
    $markerChange = false;
    $dateChange = false;
    if ($event->location_show == 0) {
        if ($data['location_show'] > 0) {
            if ($data['location_lat']) {
                // map has been switched to on (and marker dropped or preexists)
                $mapChangeOn = true;                                
            } else {
                // map has been switched to on (but no marker dropped)
                // so reset back to off
                $data['location_show'] = 0;                                
            }
        }
    } else {
        if ($data['location_show'] == 0) {
            // map has been switched to off
            $mapChangeOff = true;
        }
    }
    if ($data['location_show']) {
        if ($data['location_lat'] != $event->location_lat || $data['location_lng'] != $event->location_lng) {
            // marker has changed position
            $markerChange = true;
            // want geocode details 
            getGeo($data);
        } else if ($data['location_geo'] != $event->location_geo) {
            // geo choice has changed, i.e building or street, town etc
            $data['location'] = setLocation($data['location_geo'], $event->location_geo_building, $event->location_geo_street, $event->location_geo_city, $event->location_geo_region, $event->location_geo_country);
            //dd($data['location']);
        }
    }
    if ($data['date_unix']) {
        $data['date_unix'] = Carbon::parse($data['date_unix'])->timestamp;
        $data['date_unix_gmt'] = $data['date_unix'];
        if ($mapChangeOff) {// if map changed from on to off (update to UTC tz)
            if ($data['date_time']) {
                $data['date_time'] = Carbon::parse($data['date_time'].' '.$request->date_time_ampm)->format('H:i'); // converts to 24 hour
                $data['location_tz'] = 'Coordinated Universal Time'; // time exists so set UTC as default timezone
                $dateChange = true;
            }
        } else if ($data['date_time'] && ($mapChangeOn || $markerChange)) {// if map changed from off to on OR if map marker has changed (use lat/lng to get tz)
            $data['location_tz'] = 'Coordinated Universal Time';
            getTimezone($data);
            $dateChange = true;
        } else if ($data['date_year'] != $event->date_year || $data['date_month'] != $event->date_month || $data['date_day'] != $event->date_day || $data['date_time'] != $event->date_time) {// if time has changed
            if ($data['date_time']) {
                $data['location_tz'] = 'Coordinated Universal Time';
                if ($data['location_show'] > 0) {
                    getTimezone($data);
                }
            }
            $dateChange = true;
        }

    } else { // date_type is null
        if ($date_type != $event->date_type) { // its changed to null
            $dateChange = true;
        }
    }
    if ($dateChange) {
        // now do reorder stuff
        $events = $timeline->events;
        reorderAll($date_type, $timeline->id, $events, $data);
    }
    //dd($dateChange);
    return $dateChange;
}

function reorderAll($date_type, $timeline_id, $events, &$data) {
    if ($date_type == 1) { // year
        // YEAR
        $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
        if ($exists_year === null) { // therefore create a new year
            $data['order_ny'] = reorderNoneYear($timeline_id, $events, $data); // and put year before or after existing items
            $data['order_ym'] = 1; // order is the first ym
        } else { // adding a year item within year
            $data['order_ny'] = $exists_year['order_ny']; // order stays the same
            $data['order_ym'] = $exists_year['order_ym'] + 1; // this event gets added to the existing
        }
    } else if ($date_type == 2) { // month
        // YEAR
        $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
        if ($exists_year === null) { // therefore create a new year
            $data['order_ny'] = reorderNoneYear($timeline_id, $events, $data); // and put year before or after existing year entries
            $data['order_ym'] = 1; // order is the first ym
            $data['order_md'] = 1; // order is the first md
        } else { // adding a year item within year
            $data['order_ny'] = $exists_year['order_ny']; // order stays the same 
            // MONTH
            $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
            if ($exists_month === null) { // therefore create a new month
                $data['order_ym'] = reorderYearMonth($timeline_id, $events, $data); // and put month before or after existing month entries
                $data['order_md'] = 1; // order is the first md
            } else { // adding a month item within month
                $data['order_ym'] = $exists_month['order_ym']; // order stays the same 
                $data['order_md'] = $exists_month['order_md'] + 1; // this event gets added to the existing 
            }
        }
    } else if ($date_type == 3) { // day
        // YEAR
        $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
        if ($exists_year === null) { // therefore create a new year
            $data['order_ny'] = reorderNoneYear($timeline_id, $events, $data); // and put year before or after existing year entries
            $data['order_ym'] = 1; // order is the first ym
            $data['order_md'] = 1; // order is the first md
            $data['order_dt'] = 1; // order is the first dt
        } else { // adding a year item within year
            $data['order_ny'] = $exists_year['order_ny']; // order stays the same  
            // MONTH
            $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
            if ($exists_month === null) { // therefore create a new month
                $data['order_ym'] = reorderYearMonth($timeline_id, $events, $data); // and put month before or after existing month entries
                $data['order_md'] = 1; // order is the first md
                $data['order_dt'] = 1; // order is the first dt
            } else { // adding a month item within month
                $data['order_ym'] = $exists_month['order_ym']; // order stays the same 
                // DAY
                $exists_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->sortByDesc('order_dt')->first();
                if ($exists_day === null) { // therefore create a new day
                    $data['order_md'] = reorderMonthDay($timeline_id, $events, $data); // and put day before or after existing day entries
                    $data['order_dt'] = 1; // order is the first dt
                } else { // adding a day item within day
                    $data['order_md'] = $exists_day['order_md']; // order stays the same  
                    $data['order_dt'] = $exists_day['order_dt'] + 1; // this event gets added to the existing  
                }
            }
        }
    } else if ($date_type == 4) { // time
        // YEAR
        $exists_year = $events->where('date_year', $data['date_year'])->sortByDesc('order_ym')->first();
        if ($exists_year === null) { // therefore create a new year
            $data['order_ny'] = reorderNoneYear($timeline_id, $events, $data); // and put year before or after existing year entries
            $data['order_ym'] = 1;
            $data['order_md'] = 1;
            $data['order_dt'] = 1;
            $data['order_t'] = 1;
        } else { // adding a year item within year
            $data['order_ny'] = $exists_year['order_ny']; 
            // MONTH
            $exists_month = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->sortByDesc('order_md')->first();
            if ($exists_month === null) { // therefore create a new month
                $data['order_ym'] = reorderYearMonth($timeline_id, $events, $data); // and put month before or after existing month entries
                $data['order_md'] = 1;
                $data['order_dt'] = 1;
                $data['order_t'] = 1;
            } else { // adding a month item within month
                $data['order_ym'] = $exists_month['order_ym']; 
                // DAY
                $exists_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->sortByDesc('order_dt')->first();
                if ($exists_day === null) { // therefore create a new day
                    $data['order_md'] = reorderMonthDay($timeline_id, $events, $data); // and put day before or after existing day entries
                    $data['order_dt'] = 1;
                    $data['order_t'] = 1;
                } else { // adding a day item within day
                    $data['order_md'] = $exists_day['order_md']; 
                    // TIME
                    $exists_time = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->where('date_unix', $data['date_unix'])->where('date_unix_gmt', $data['date_unix_gmt'])->sortByDesc('order_dt')->first();
                    if ($exists_time === null) { // therefore create a new time
                        $data['order_dt'] = reorderDayTime($timeline_id, $events, $data);  
                        $data['order_t'] = 1;
                    } else { // adding a time within a time
                        $data['order_dt'] = $exists_time['order_dt'];
                        $data['order_t'] = $exists_time['order_t'] + 1;  // this event gets added to the existing 
                    }
                }
            }
        }
    } else {
        $data['order_ny'] = $events->max('order_ny') + 1;
    }
}

function reorderNoneYear($timeline_id, $events, &$data) {
    $latest_year = $events->whereNotNull('date_year')->max('date_year');
    if ($data['date_year'] >= $latest_year) { // event happens after
        return $events->max('order_ny') + 1;
    } else { // event happens before
        $after_year = $events->whereNotNull('date_year')->where('date_year','>', $data['date_year'])->sortBy('date_year')->first();
        Event::where('timeline_id', $timeline_id)->where('order_ny','>=', $after_year['order_ny'])->increment('order_ny');
        return $after_year['order_ny'];
    }
}

function reorderYearMonth($timeline_id, $events, &$data) {
    $latest_month = $events->where('date_year', $data['date_year'])->whereNotNull('date_month')->max('date_month');
    if ($data['date_month'] >= $latest_month) { // event happens after
        return $events->where('date_year', $data['date_year'])->max('order_ym') + 1;
    } else { // event happens before
        $after_month = $events->where('date_year', $data['date_year'])->whereNotNull('date_month')->where('date_month','>', $data['date_month'])->sortBy('date_month')->first();
        Event::where('timeline_id', $timeline_id)->where('date_year', $data['date_year'])->where('order_ym','>=', $after_month['order_ym'])->increment('order_ym');
        return $after_month['order_ym'];
    }
}

function reorderMonthDay($timeline_id, $events, &$data) {
    $latest_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->whereNotNull('date_day')->max('date_day');
    if ($data['date_day'] >= $latest_day) { // event happens after
        return $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('order_md') + 1;
    } else { // event happens before
        $after_day = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->whereNotNull('date_day')->where('date_day','>', $data['date_day'])->sortBy('date_day')->first();
        Event::where('timeline_id', $timeline_id)->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('order_md','>=', $after_day['order_md'])->increment('order_md');
        return $after_day['order_md'];
    }
}

function reorderDayTime($timeline_id, $events, &$data) {
    $latest_time = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->whereNotNull('date_time')->max('date_unix_gmt');
    if ($data['date_unix_gmt'] >= $latest_time) { // event happens after 
        return $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->max('order_dt') + 1;
    } else { // event happens before 
        $after_time = $events->where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->whereNotNull('date_time')->where('date_unix_gmt','>', $data['date_unix_gmt'])->sortBy('date_unix_gmt')->first();
        Event::where('timeline_id', $timeline_id)->where('date_year', $after_time['date_year'])->where('date_month', $after_time['date_month'])->where('date_day', $after_time['date_day'])->where('order_dt','>=', $after_time['order_dt'])->increment('order_dt');
        return $after_time['order_dt']; 
    }  
}

function firstNonEmpty(array $list) {
    foreach ($list as $value) {
        if ($value) {
            return $value;
        }
    }
    return null;
}