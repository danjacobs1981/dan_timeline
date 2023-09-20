<?php

use Illuminate\Support\Facades\DB;

use App\Models\Timeline;
use App\Models\Event;

use Carbon\Carbon;
use Carbon\CarbonInterface;

  
function helperUniqueId($table)
{
    $number = '';
    do {
        $number = rand(11111,99999);
    } while ( !empty(DB::table($table)->where('id', $number)->first(['id'])) );
    return $number;
}

function helperCurl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // this is insecure - needs certs?
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this is insecure - needs certs?
    $returnedJson = curl_exec($ch);
    curl_close($ch);
    return json_decode($returnedJson);
}


// this should be elsewhere
function updateTimeline($timeline_id)
{
    
    $timeline_events = Timeline::find($timeline_id)->events;

    $order_overall = 1;

    $prevDate = $eventNone = $eventCurrent = null;

    foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events) {

        foreach ($events->unique('order_ny') as $event) {

            if ($event->date_type === null) {

                $period = null;

                if($eventNone) {

                    $period = 'Sometime after '.$prevDate->format('Y');

                }

                // NONE
                Event::find($event->id)->update(['order_overall' => $order_overall++, 'period' => $period]);

                $eventCurrent = null;

                $eventNone = null;

                $prevDate = null;
                
            } else {

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
                                            
                                        foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_dt')->unique('order_dt') as $event) {
                                            
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

}

