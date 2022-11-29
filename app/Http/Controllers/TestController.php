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
       
        $all_events = Event::all()->where('id_timeline', '123')->sortBy('order_ny')->groupBy('order_ny');

        

        
        //Illuminate\Database\Eloquent\Collection
        //dd($events);

        //$ny = $events->whereIn('date_type', [null, 1])->sortBy('order_ny')->groupBy('order_ny')->toArray();
        //$ny = $events->sortBy('order_ny')->groupBy('order_ny');
        //$ym = $events->sortBy('order_ym')->unique('order_ym');

   //dd($events);

        //$ym = $ny->where('date_year', '!=', null)->whereNull('date_month')->sortBy('order_period')->groupBy('date_year');




        //dd($days);




        /*->where(function($query) {
            $query->where('date_year', '!=', null)->groupBy(['date_year','date_month','date_day','date_unix_gmt']);
        });*/
        //->where('date_year', '!=', null)
        //->groupBy(['date_year','date_month','date_day','date_unix_gmt']) // if not null conditions ??
        //->groupBy('date_year') // if not null conditions ??
        //->orderBy('date_month', 'asc')
        //->orderBy('date_day', 'asc')
        //->orderBy('date_unix_gmt', 'asc')

 
        
        return view('layouts.portal.pages.event.index',compact('all_events'));

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
        $data['order_period'] = 0; // old
        
        if($date_type == 1) {

            // see if year exists and if so get its latest order_ym
            $exists_year = Event::where('date_year', $data['date_year'])->orderBy('order_ym', 'desc')->first();
            if ($exists_year === null) {
                $latest_year = Event::whereNotNull('date_year')->max('date_year');
                if ($latest_year <= $data['date_year']) { 
                    // new year is greater so add to very end of current order
                    $data['order_ny'] = Event::max('order_ny') + 1;
                } else { 
                    // new year is less so find it's next_year
                    $next_year = Event::whereNotNull('date_year')->where('date_year','>', $data['date_year'])->orderBy('date_year', 'asc')->first();
                    // increment order for that year / all items ordered higher
                    Event::where('order_ny','>=', $next_year['order_ny'])->increment('order_ny');
                    // apply order to current year (one less than next_year)
                    $data['order_ny'] = Event::where('date_year', $next_year['date_year'])->max('order_ny') - 1; 
                }      
                $data['order_ym'] = 1;
            } else {
                $data['order_ny'] = $exists_year['order_ny']; 
                $data['order_ym'] = $exists_year['order_ym'] + 1;
            }

        } else if ($date_type == 2) {
            
            // see if year exists and if so get its latest order_ym
            $exists_year = Event::where('date_year', $data['date_year'])->orderBy('order_ym', 'desc')->first();
            if ($exists_year === null) {
                $latest_year = Event::whereNotNull('date_year')->max('date_year');
                if ($latest_year <= $data['date_year']) { 
                    // new year is greater so add to very end of current order
                    $data['order_ny'] = Event::max('order_ny') + 1;
                } else { 
                    // new year is less so find it's next_year
                    $next_year = Event::whereNotNull('date_year')->where('date_year','>', $data['date_year'])->orderBy('date_year', 'asc')->first();
                    // increment order for that year / all items ordered higher
                    Event::where('order_ny','>=', $next_year['order_ny'])->increment('order_ny');
                    // apply order to current year (one less than next_year)
                    $data['order_ny'] = Event::where('date_year', $next_year['date_year'])->max('order_ny') - 1; 
                }      
                $data['order_ym'] = 1;
                $data['order_md'] = 1;
            } else {
                $data['order_ny'] = $exists_year['order_ny']; 
                // see if month exists and if so get its latest order_md
                $exists_month = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->orderBy('order_md', 'desc')->first();
                if ($exists_month === null) {
                    $latest_month = Event::where('date_year', $data['date_year'])->max('date_month');
                    if ($latest_month <= $data['date_month']) { 
                        // new month is greater so add to very end of current order
                        $data['order_ym'] = Event::where('date_year', $data['date_year'])->max('order_ym') + 1;
                    } else { 
                        // new month is less so find it's next_month
                        $next_month = Event::where('date_year', $data['date_year'])->where('date_month','>', $data['date_month'])->orderBy('date_month', 'asc')->first();
                        // increment order for that month / all items ordered higher
                        Event::where('date_year', $next_month['date_year'])->where('order_ym','>=', $next_month['order_ym'])->increment('order_ym');
                        // apply order to current month (one less than next_month)
                        $data['order_ym'] = Event::where('date_year', $next_month['date_year'])->where('date_month', $next_month['date_month'])->max('order_ym') - 1; 
                    }      
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
                $latest_year = Event::whereNotNull('date_year')->max('date_year');
                if ($latest_year <= $data['date_year']) { 
                    // new year is greater so add to very end of current order
                    $data['order_ny'] = Event::max('order_ny') + 1;
                } else { 
                    // new year is less so find it's next_year
                    $next_year = Event::whereNotNull('date_year')->where('date_year','>', $data['date_year'])->orderBy('date_year', 'asc')->first();
                    // increment order for that year / all items ordered higher
                    Event::where('order_ny','>=', $next_year['order_ny'])->increment('order_ny');
                    // apply order to current year (one less than next_year)
                    $data['order_ny'] = Event::where('date_year', $next_year['date_year'])->max('order_ny') - 1; 
                }      
                $data['order_ym'] = 1;
                $data['order_md'] = 1;
            } else {
                $data['order_ny'] = $exists_year['order_ny']; 
                // see if month exists and if so get its latest order_md
                $exists_month = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->orderBy('order_md', 'desc')->first();
                if ($exists_month === null) {
                    $latest_month = Event::where('date_year', $data['date_year'])->max('date_month');
                    if ($latest_month <= $data['date_month']) { 
                        // new month is greater so add to very end of current order
                        $data['order_ym'] = Event::where('date_year', $data['date_year'])->max('order_ym') + 1;
                    } else { 
                        // new month is less so find it's next_month
                        $next_month = Event::where('date_year', $data['date_year'])->where('date_month','>', $data['date_month'])->orderBy('date_month', 'asc')->first();
                        // increment order for that month / all items ordered higher
                        Event::where('date_year', $next_month['date_year'])->where('order_ym','>=', $next_month['order_ym'])->increment('order_ym');
                        // apply order to current month (one less than next_month)
                        $data['order_ym'] = Event::where('date_year', $next_month['date_year'])->where('date_month', $next_month['date_month'])->max('order_ym') - 1; 
                    }      
                    $data['order_md'] = 1;
                } else {
                    $data['order_ym'] = $exists_month['order_ym']; 
                    // see if day exists and if so get its latest order_dt
                    $exists_day = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->orderBy('order_dt', 'desc')->first();
                    if ($exists_day === null) {
                        $latest_day = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('date_day');
                        if ($latest_day <= $data['date_day']) { 
                            // new day is greater so add to very end of current order
                            $data['order_md'] = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('order_md') + 1;
                        } else { 
                            // new day is less so find it's next_day
                            $next_day = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day','>', $data['date_day'])->orderBy('date_day', 'asc')->first();
                            // increment order for that dday / all items ordered higher
                            Event::where('date_year', $next_day['date_year'])->where('date_month', $next_day['date_month'])->where('order_md','>=', $next_day['order_md'])->increment('order_md');
                            // apply order to current day (one less than next_day)
                            $data['order_md'] = Event::where('date_year', $next_day['date_year'])->where('date_month', $next_day['date_month'])->where('date_day', $next_day['date_day'])->max('order_md') - 1; 
                        }      
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
                $latest_year = Event::whereNotNull('date_year')->max('date_year');
                if ($latest_year <= $data['date_year']) { 
                    // new year is greater so add to very end of current order
                    $data['order_ny'] = Event::max('order_ny') + 1;
                } else { 
                    // new year is less so find it's next_year
                    $next_year = Event::whereNotNull('date_year')->where('date_year','>', $data['date_year'])->orderBy('date_year', 'asc')->first();
                    // increment order for that year / all items ordered higher
                    Event::where('order_ny','>=', $next_year['order_ny'])->increment('order_ny');
                    // apply order to current year (one less than next_year)
                    $data['order_ny'] = Event::where('date_year', $next_year['date_year'])->max('order_ny') - 1; 
                }      
                $data['order_ym'] = 1;
                $data['order_md'] = 1;
                $data['order_dt'] = 1;
            } else {
                $data['order_ny'] = $exists_year['order_ny']; 
                // see if month exists and if so get its latest order_md
                $exists_month = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->orderBy('order_md', 'desc')->first();
                if ($exists_month === null) {
                    $latest_month = Event::where('date_year', $data['date_year'])->max('date_month');
                    if ($latest_month <= $data['date_month']) { 
                        // new month is greater so add to very end of current order
                        $data['order_ym'] = Event::where('date_year', $data['date_year'])->max('order_ym') + 1;
                    } else { 
                        // new month is less so find it's next_month
                        $next_month = Event::where('date_year', $data['date_year'])->where('date_month','>', $data['date_month'])->orderBy('date_month', 'asc')->first();
                        // increment order for that month / all items ordered higher
                        Event::where('date_year', $next_month['date_year'])->where('order_ym','>=', $next_month['order_ym'])->increment('order_ym');
                        // apply order to current month (one less than next_month)
                        $data['order_ym'] = Event::where('date_year', $next_month['date_year'])->where('date_month', $next_month['date_month'])->max('order_ym') - 1; 
                    }      
                    $data['order_md'] = 1;
                    $data['order_dt'] = 1;
                } else {
                    $data['order_ym'] = $exists_month['order_ym']; 
                    // see if day exists and if so get its latest order_dt
                    $exists_day = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->orderBy('order_dt', 'desc')->first();
                    if ($exists_day === null) {
                        $latest_day = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('date_day');
                        if ($latest_day <= $data['date_day']) { 
                            // new day is greater so add to very end of current order
                            $data['order_md'] = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->max('order_md') + 1;
                        } else { 
                            // new day is less so find it's next_day
                            $next_day = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day','>', $data['date_day'])->orderBy('date_day', 'asc')->first();
                            // increment order for that day / all items ordered higher
                            Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('order_md','>=', $next_day['order_md'])->increment('order_md');
                            // apply order to current day (one less than next_day)
                            $data['order_md'] = Event::where('date_year', $next_day['date_year'])->where('date_month', $next_day['date_month'])->where('date_day', $next_day['date_day'])->max('order_md') - 1; 
                        }      
                        $data['order_dt'] = 1;
                    } else {
                        $data['order_md'] = $exists_day['order_md']; 
                        $latest_time = Event::where('date_year', $data['date_year'])->where('date_month', $data['date_month'])->where('date_day', $data['date_day'])->max('date_unix_gmt');
                        if ($latest_time <= $data['date_unix_gmt']) { 
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
        $data['id_timeline'] = "123";
        
        //dd($data);

        // update the table
        $show = Event::create($data);
   
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
