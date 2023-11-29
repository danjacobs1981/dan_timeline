<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Timeline;
use App\Models\Event;
use App\Models\Select;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class TimelineEditController extends Controller
{

    public function settings(Timeline $timeline, Request $request)
    {

        // ajax for timeline settings
        if($request->ajax()){
        
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $this->validate($request,[
                    'title' => 'required|max:255', // this needs to be decent validation for title
                    'map' => 'boolean',
                    'comments' => 'boolean',
                    'comments_event' => 'boolean',
                    'social' => 'boolean',
                    'collab' => 'boolean',
                    'profile' => 'boolean',
                    'filter' => 'boolean',
                    'adverts' => 'boolean'
                ]);

                $timeline->title = $request->title;
                $timeline->slug = Str::slug($request->title, "-");
                $timeline->map = $request->map;
                $timeline->comments = $request->comments;
                $timeline->comments_event = $request->comments_event;
                $timeline->social = $request->social;
                $timeline->collab = $request->collab;
                $timeline->profile = $request->profile;

                if(auth()->user()->premium) {
                    $timeline->filter = $request->filter;
                    $timeline->adverts = $request->adverts;
                } else {
                    $timeline->filter = 0;
                    $timeline->adverts = 1;
                }
                
                $timeline->save();

                return response()->json([
                    'status'=> 200,
                    'message' => 'Settings updated successfully',
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function privacy(Timeline $timeline, Request $request)
    {

        // ajax for timeline privacy
        if($request->ajax()){
        
            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $timeline->privacy = $request->privacy;
                
                $timeline->save();

                return response()->json([
                    'status'=> 200,
                    'message' => 'Privacy updated successfully',
                    'result' => $timeline->privacy
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function privacyShare(Timeline $timeline, Request $request)
    {

        // ajax for timeline privacy sharing email addresses
        if($request->ajax()){

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $data = $request->data;

                Select::where('timeline_id', $timeline->id)->delete();

                if($data) {
                    foreach($data as $value) {
                        $store = new Select;
                        $store->timeline_id = $timeline->id;
                        $store->email = $value['value'];
                        $store->save();
                    }
                }

                return response()->json([
                    'status'=> 200,
                    'message' => 'Privacy sharing updated successfully',
                    'result' => $data
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function reorder(Timeline $timeline, Request $request)
    {

        // ajax for reordering a dragged event
        if($request->ajax()){

            if ($timeline && $timeline->user_id === auth()->user()->id) {

                $id = $request->id;
                $local = $request->local;
                $local_after = $request->boolean('local_after');

                // get event and it's date_type
                $event = Event::select('date_type')->where('id', $id)->first();

                //dd($event->date_type);

                $order_date = 'order_ny';

                if ($event->date_type == 1) {
                    $order_date = 'order_ym';
                } else if ($event->date_type == 2) {
                    $order_date = 'order_md';
                } else if ($event->date_type == 3) {
                    $order_date = 'order_dt';
                } else if ($event->date_type == 4) {
                    $order_date = 'order_t';
                }

                if ($local_after == 'true') { 
                    $local = $local + 1;
                }

                Event::where('timeline_id', $timeline->id)->where($order_date,'>=', $local)->increment($order_date);
                Event::find($id)->update([$order_date => $local]);

                return response()->json([
                    'status'=> 200,
                    'message' => 'Event reordered',
                    'timeline_id' => $timeline->id,
                    'event_id' => $id,
                ]);

            } else {

                return response()->json([
                    'status'=> 401,
                    'message' => 'Authentication error',
                ]);

            }

        }
        
    }

    public function process(Timeline $timeline)
    {

        // ajax for processing of events
        if ($timeline) {

            //dd($timeline->events); // collection

            $order_overall = 1;
        
            $prevDate = $eventNone = $eventCurrent = null;
        
            foreach($timeline->events->sortBy('order_ny')->groupBy('order_ny') as $events) {
        
                foreach ($events->unique('order_ny') as $event) {
        
                    if ($event->date_type === null) { // n
        
                        $period = null;
        
                        if($eventNone) {
        
                            $period = 'Sometime after '.$prevDate->format('Y');
        
                        }
        
                        // NONE
                        Event::find($event->id)->update(['order_overall' => $order_overall++, 'period' => $period]);
        
                        $eventCurrent = null;
        
                        $eventNone = null;
        
                        $prevDate = null;
                        
                    } else { // y
        
                        $eventNone = 1;
                        
                        foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event) {
        
                            $dt = Carbon::createFromTimestamp($event->date_unix);
                            
                            $period = $difference = null;
                            
                            // DURING YEAR
        
                            $period = 'During '.$dt->format('Y');
                            
                            if ($prevDate && $dt > $prevDate && $eventCurrent >= $event->date_type) {
        
                                $difference = $dt->diffForHumans($prevDate, ['syntax' => CarbonInterface::DIFF_ABSOLUTE, 'join' => ', ', 'parts' => 4]).' later';
        
                            }
        
                            Event::find($event->id)->update(['period' => $period, 'difference' => $difference]); // (period + diff) year
        
                            if ($event->date_type === 1) {
        
                                // YEAR
                                Event::find($event->id)->update(['order_overall' => $order_overall++]);
        
                                $eventCurrent = $event->date_type;
        
                                $prevDate = $dt;
        
                            } else {
        
                                foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event) { 
        
                                    $dt = Carbon::createFromTimestamp($event->date_unix);
        
                                    $period = $difference = null;
        
                                    // DURING YEAR, IN MONTH
        
                                    $period = 'In '.$dt->format('F, Y');
        
                                    if ($prevDate && $dt > $prevDate && $eventCurrent >= $event->date_type) {
        
                                        $difference = $dt->diffForHumans($prevDate, ['syntax' => CarbonInterface::DIFF_ABSOLUTE, 'join' => ', ', 'parts' => 4]).' later';
        
                                    }
                                    
                                    Event::find($event->id)->update(['period' => $period, 'difference' => $difference]); // (period + diff) year & month
        
                                    if ($event->date_type === 2) {
            
                                        // MONTH
                                        Event::find($event->id)->update(['order_overall' => $order_overall++]);
        
                                        $eventCurrent = $event->date_type;
        
                                        $prevDate = $dt;
            
                                    } else {
                                        
                                        foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event) {
        
                                            $dt = Carbon::createFromTimestamp($event->date_unix);
        
                                            $period = $period_short = $difference = null;
        
                                            // DURING YEAR, IN MONTH, ON DAY
        
                                            $period = 'On '.$dt->format('l jS \o\f F, Y');
                                            $period_short = 'On '.$dt->format('D jS \o\f M, Y');
        
                                            if ($prevDate && $dt > $prevDate && $eventCurrent >= $event->date_type) {
        
                                                $difference = $dt->diffForHumans($prevDate, ['syntax' => CarbonInterface::DIFF_ABSOLUTE, 'join' => ', ', 'parts' => 4]).' later';
                                                
                                            }
        
                                            Event::find($event->id)->update(['period' => $period, 'period_short' => $period_short, 'difference' => $difference]); // (period + diff) year & month & day
        
                                            if ($event->date_type === 3) {
            
                                                // DAY
                                                Event::find($event->id)->update(['order_overall' => $order_overall++]);
        
                                                $eventCurrent = $event->date_type;
        
                                                $prevDate = $dt;
                                            
                                            } else {

                                                foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_t')->unique('order_t') as $event) {
                                                    
                                                    $dt = Carbon::createFromTimestamp($event->date_unix);
                                                    $dt_gmt = Carbon::createFromTimestamp($event->date_unix_gmt);
        
                                                    $period = $difference = null;
                                                    
                                                    // DURING YEAR, IN MONTH, ON DAY, AT TIME
        
                                                    $period = 'At '.$dt->format('h:ia \o\n l jS \o\f F, Y');
                                                    $period_short = 'At '.$dt->format('h:ia \o\n D jS \o\f M, Y');
        
                                                    if ($prevDate && $dt_gmt > $prevDate && $eventCurrent >= $event->date_type) {
        
                                                        $difference = $dt_gmt->diffForHumans($prevDate, ['syntax' => CarbonInterface::DIFF_ABSOLUTE, 'join' => ', ', 'parts' => 4]).' later';
                                                        
                                                    }
        
                                                    Event::find($event->id)->update(['period' => $period, 'period_short' => $period_short, 'difference' => $difference]); // (period + diff) year & month & day & time
        
                                                    if ($event->date_type === 4) {
        
                                                        // TIME
                                                        Event::find($event->id)->update(['order_overall' => $order_overall++]);
        
                                                        $eventCurrent = $event->date_type;
        
                                                        $prevDate = $dt_gmt;
        
                                                    }
            
                                                }
            
                                            }
        
                                        }
            
                                    }
            
                                }
        
                            }
        
                        }
        
                    }
        
                }
            
            }

            return response()->json([
                'status'=> 200,
                'message' => 'Timeline events reordered'
            ]);

        } 

    }

    public function showModalPrivacy(Timeline $timeline)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $privateUsers = $timeline->privateUsers;

            $modal_title = 'Privacy Options';
            $modal_buttons = array('close' => 'Done');
            $route = 'layouts.portal.snippets.modal.edit-privacy';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'privateUsers'));

        } else {

            abort(401);

        }
 
    }

    public function showModalPrivacyShare(Timeline $timeline)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $privateUsers = $timeline->privateUsers->toJson();

            $modal_title = 'Share Timeline Privately';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Save');
            $route = 'layouts.portal.snippets.edit-privacy-share';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'privateUsers'));

        } else {

            abort(401);

        }
 
    }

    public function showModalDelete(Timeline $timeline)
    {

        if ($timeline && $timeline->user_id === auth()->user()->id) {

            $event_count = $timeline->events->count();

            $modal_title = 'Delete Timeline';
            $modal_buttons = array('close' => 'Cancel', 'action' => 'Delete Timeline', 'form' => 'formDelete');
            $route = 'layouts.portal.snippets.edit-delete';
            return view('layouts.modal.master', compact('modal_title', 'modal_buttons', 'route', 'timeline', 'event_count'));

        } else {

            abort(401);

        }
 
    }


}
