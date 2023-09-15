<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $timeline_events = Event::all()->where('timeline_id', '123')->sortBy('order_ny')->groupBy('order_ny');

        return view('layouts.portal.pages.event.index',compact('timeline_events'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('layouts.portal.pages.event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
                //'location_lat' => ['nullable', 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                //'location_lng' => ['nullable', 'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                'location_lat' => 'required_with:date_time',
                'location_lng' => 'required_with:date_time',
                'location_geo_street' => 'boolean',
                'location_show' => 'boolean',
            ],
            [   
                'title.required'    => 'Please enter a title',
                'title.max'    => 'Please enter a title (max 255 chars)',
                'date_unix.date'      => 'Please enter a valid date'
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
        
        if($date_type == 1) {

            // see if year exists and if so get its latest order_ym
            $exists_year = Event::where('date_year', $data['date_year'])->orderBy('order_ym', 'desc')->first();
            if ($exists_year === null) {
                $latest_moment = Event::whereNotNull('date_year')->max('date_year');
                reorder($data, $latest_moment, 'date_year', 'order_ny');
                $data['order_ym'] = 1;
            } else {
                $data['order_ny'] = $exists_year['order_ny']; 
                $data['order_ym'] = $exists_year['order_ym'] + 1;
            }

        } else if ($date_type == 2) {
            
            // see if year exists and if so get its latest order_ym
            $exists_year = Event::where('date_year', $data['date_year'])->orderBy('order_ym', 'desc')->first();
            if ($exists_year === null) {
                $latest_moment = Event::whereNotNull('date_year')->max('date_year');
                reorder($data, $latest_moment, 'date_year', 'order_ny');     
                $data['order_ym'] = 1;
                $data['order_md'] = 1;
            } else {
                $data['order_ny'] = $exists_year['order_ny']; 
                // see if month exists and if so get its latest order_md
                $exists_month = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->orderBy('order_md', 'desc')->first();
                if ($exists_month === null) {
                    $latest_moment = Event::where('date_year', $data['date_year'])->max('date_month');
                    reorder($data, $latest_moment, 'date_month', 'order_ym');     
                    $data['order_md'] = 1;
                } else {
                    $data['order_ym'] = $exists_month['order_ym']; 
                    $data['order_md'] = $exists_month['order_md'] + 1; 
                }
            }

        } else if ($date_type == 3) {

            // see if year exists and if so get its latest order_ym
            $exists_year = Event::where('date_year', $data['date_year'])->orderBy('order_ym', 'desc')->first();
            if ($exists_year === null) {
                //dd($data['date_year']);
                $latest_moment = Event::whereNotNull('date_year')->max('date_year');
                reorder($data, $latest_moment, 'date_year', 'order_ny'); 
                //dd($data['order_ny']);
                $data['order_ym'] = 1;
                $data['order_md'] = 1;
            } else {
                $data['order_ny'] = $exists_year['order_ny']; 
                // see if month exists and if so get its latest order_md
                $exists_month = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->orderBy('order_md', 'desc')->first();
                if ($exists_month === null) {
                    $latest_moment = Event::where('date_year', $data['date_year'])->max('date_month');
                    reorder($data, $latest_moment, 'date_month', 'order_ym');  
                    $data['order_md'] = 1;
                } else {
                    $data['order_ym'] = $exists_month['order_ym']; 
                    // see if day exists and if so get its latest order_dt
                    $exists_day = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->orderBy('order_dt', 'desc')->first();
                    if ($exists_day === null) {
                        $latest_moment = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('date_day');
                        reorder($data, $latest_moment, 'date_day', 'order_md');   
                        $data['order_dt'] = 1;
                    } else {
                        $data['order_md'] = $exists_day['order_md']; 
                        $data['order_dt'] = $exists_day['order_dt'] + 1; 
                    }
                }
            }

        } else if ($date_type == 4) {

            // see if year exists and if so get its latest order_ym
            $exists_year = Event::where('date_year', $data['date_year'])->orderBy('order_ym', 'desc')->first();
            if ($exists_year === null) {
                $latest_moment = Event::whereNotNull('date_year')->max('date_year');
                reorder($data, $latest_moment, 'date_year', 'order_ny'); 
                $data['order_ym'] = 1;
                $data['order_md'] = 1;
                $data['order_dt'] = 1;
            } else {
                $data['order_ny'] = $exists_year['order_ny']; 
                // see if month exists and if so get its latest order_md
                $exists_month = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->orderBy('order_md', 'desc')->first();
                if ($exists_month === null) {
                    $latest_moment = Event::where('date_year', $data['date_year'])->max('date_month');
                    reorder($data, $latest_moment, 'date_month', 'order_ym');  
                    $data['order_md'] = 1;
                    $data['order_dt'] = 1;
                } else {
                    $data['order_ym'] = $exists_month['order_ym']; 
                    // see if day exists and if so get its latest order_dt
                    $exists_day = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->orderBy('order_dt', 'desc')->first();
                    if ($exists_day === null) {
                        $latest_moment = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('date_day');
                        reorder($data, $latest_moment, 'date_day', 'order_md');     
                        $data['order_dt'] = 1;
                    } else {
                        $data['order_md'] = $exists_day['order_md']; 
                        // sort the position based on its time
                        $latest_moment = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->max('date_unix_gmt');
                        if ($latest_moment <= $data['date_unix_gmt']) { 
                            // new time is greater so add to very end of current order
                            $data['order_dt'] = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->max('order_dt') + 1;
                        } else { 
                            // new time is less so find it's next_time
                            $next_time = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->where('date_unix_gmt','>', $data['date_unix_gmt'])->orderBy('date_unix_gmt', 'asc')->first();
                            // increment order for that time / all items ordered higher
                            Event::where('date_year', $next_time['date_year'])->where('date_month', $next_time['date_month'])->where('date_day', $next_time['date_day'])->where('order_dt','>=', $next_time['order_dt'])->increment('order_dt');
                            // apply order to current time (one less than next_time)
                            $data['order_dt'] = Event::where('date_year', $next_time['date_year'])->where('date_month', $next_time['date_month'])->where('date_day', $next_time['date_day'])->where('date_unix_gmt', $next_time['date_unix_gmt'])->max('order_dt') - 1; 
                        }      
                    }
                }
            }

        } else {

            $data['order_ny'] = Event::max('order_ny') + 1;
            
        }

        // other data
        $data['date_type'] = $date_type;
        $data['id'] = helperUniqueId('events');
        $data['timeline_id'] = 22163;
        
        //dd($data);

        // update the table
        $show = Event::create($data);

        // set order_overall for all events



   
        return redirect('/events')->with('success', 'Event successfully saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect('/events')->with('success', 'Event successfully deleted');
    }
}

function reorder(&$data, $latest_moment, $data_date, $data_order) {
    if ($latest_moment <= $data[$data_date]) { 
        $data[$data_order] = Event::max($data_order) + 1;
        //dd($data['order_ny']);
    } else { 
        $next_year = Event::whereNotNull($data_date)->where($data_date,'>', $data[$data_date])->orderBy($data_date, 'asc')->first();
        Event::where($data_order,'>=', $next_year[$data_order])->increment($data_order);
        $data[$data_order] = Event::where($data_date, $next_year[$data_date])->max($data_order) - 1; 
    }
}
