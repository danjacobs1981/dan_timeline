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

            if ($event->date_year === null) {

                $period_none = null;

                if($events->first && $eventNone) {

                    $period_none = 'Sometime after '.$prevDate->format('Y');

                }

                // NONE
                Event::find($event->id)->update(['order_overall' => $order_overall++, 'period_none' => $period_none]);

                $eventCurrent = null;

                $eventNone = null;

                $prevDate = null;
                
            } else {

                $eventNone = 1;
                
                foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event) {
                    
                    if ($events->first) {

                        $period_year = $difference_year = null;

                        $dt = Carbon::create()->year($event->date_year);

                        $period_year = 'During '.$dt->format('Y');
                        
                        if ($prevDate && $eventCurrent) {

                            // good
                            $difference_year = '(year section (year) | '.$event->id.' | prev date type: '.$eventCurrent.') Around '.$dt->diffForHumans($prevDate, ['parts' => 1, 'options' => Carbon::ROUND, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]).' later'; // Around 9 years later
                            
                        }

                        Event::find($event->id)->update(['period_year' => $period_year, 'difference_year' => $difference_year]); // (period + diff) year

                        $prevDate = $dt;

                    }

                    if ($event->date_month === null) {

                        // YEAR
                        Event::find($event->id)->update(['order_overall' => $order_overall++]);

                        $eventCurrent = 1;

                    } else {

                        foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event) { 

                            if ($events->first) {

                                $period_year = $period_month = $difference_year = $difference_month = null;
    
                                $dt = Carbon::create()->year($event->date_year)->month($event->date_month);
    
                                $period_year = 'During '.$dt->format('Y');
                                $period_month = 'In '.$dt->format('F, Y');

                                if ($prevDate && $eventCurrent) {

                                    // good
                                    $difference_year = '(month section (year) | '.$event->id.' | prev date type: '.$eventCurrent.') Around '.$dt->diffForHumans($prevDate, ['parts' => 1, 'options' => Carbon::ROUND, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]).' later'; // Around 9 years later

                                    if ($eventCurrent === 2) {
                                        // good but showing if a year above
                                        $difference_month = '((2) month section (month) | '.$event->id.' | prev date type: '.$eventCurrent.') '.$dt->diffForHumans($prevDate, ['parts' => 1, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]).' later'; // 3 months later
                                    } else if ($eventCurrent === 3 || $eventCurrent === 4) {
                                        // unknown
                                        $difference_month = '((3 or 4) month section (month) | '.$event->id.' | prev date type: '.$eventCurrent.') Around '.$dt->diffForHumans($prevDate, ['parts' => 1, 'options' => Carbon::ROUND, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]).' later'; // Around 3 months later
                                    }
    
                                }
    
                                Event::find($event->id)->update(['period_year' => $period_year, 'period_month' => $period_month, 'difference_year' => $difference_year, 'difference_month' => $difference_month]); // (period + diff) year & month
                                        
                                $prevDate = $dt;

                            }
                            
                            if ($event->date_day === null) {
    
                                // MONTH
                                Event::find($event->id)->update(['order_overall' => $order_overall++]);

                                $eventCurrent = 2;
    
                            } else {
                                
                                foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event) {
                                    
                                    if ($events->first) {

                                        $period_year = $period_month = $period_day = $difference_year = $difference_month = $difference_day = null;
                                        
                                        $dt = Carbon::create()->year($event->date_year)->month($event->date_month)->day($event->date_day);
    
                                        $period_year = 'During '.$dt->format('Y');
                                        $period_month = 'In '.$dt->format('F, Y');
                                        $period_day = 'On '.$dt->format('l jS \o\f F, Y');

                                        if ($prevDate && $eventCurrent) {

                                            $difference_year = '(day section (year) | '.$event->id.' | prev date type: '.$eventCurrent.') Around '.$dt->diffForHumans($prevDate, ['parts' => 1, 'options' => Carbon::ROUND, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]).' later'; // Around 9 years later

                                            $difference_month = '(day section (month) | '.$event->id.' | prev date type: '.$eventCurrent.') '.$dt->diffForHumans($prevDate, ['parts' => 1, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]).' later'; // 3 months later

                                            if ($eventCurrent === 3) {

                                               $difference_day = '((3) day section (day) | '.$event->id.' | prev date type: '.$eventCurrent.') '.$dt->diffForHumans($prevDate, ['parts' => 2, 'syntax' => CarbonInterface::DIFF_ABSOLUTE]).' later'; // 3 months later

                                            }
                                            
                                        }
    
                                        Event::find($event->id)->update(['period_year' => $period_year, 'period_month' => $period_month, 'period_day' => $period_day, 'difference_year' => $difference_year, 'difference_month' => $difference_month, 'difference_day' => $difference_day]); // (period + diff) year & month & day
                                        
                                        $prevDate = $dt;
    
                                    }
                                    
                                    if ($event->date_time === null) {
    
                                        // DAY
                                        Event::find($event->id)->update(['order_overall' => $order_overall++]);

                                        $eventCurrent = 3;
                                    
                                    } else {
                                            
                                        foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_dt')->unique('order_dt') as $event) {
                                            
                                            if ($events->first) {

                                                $period_year = $period_month = $period_day = $period_time = $difference_year = $difference_month = $difference_day = $difference_time = null;
                                                
                                                $dt = Carbon::createFromTimestamp($event->date_unix);
                                                $dt_gmt = Carbon::createFromTimestamp($event->date_unix_gmt);
    
                                                $period_year = 'During '.$dt->format('Y');
                                                $period_month = 'In '.$dt->format('F, Y');
                                                $period_day = 'On '.$dt->format('l jS \o\f F, Y');
                                                $period_time = 'At '.$dt->format('h:ia \o\n l jS \o\f F, Y');
    
                                                if ($prevDate && $eventCurrent) {
    
                                                    $difference_time = $dt_gmt->longRelativeToOtherDiffForHumans($prevDate, 2);
                                                    
                                                }
    
                                                Event::find($event->id)->update(['period_year' => $period_year, 'period_month' => $period_month, 'period_day' => $period_day, 'period_time' => $period_time, 'difference_year' => $difference_year, 'difference_month' => $difference_month, 'difference_day' => $difference_day, 'difference_time' => $difference_time]); // (period + diff) year & month & day & time
                                                                                                                           
                                                $prevDate = $dt_gmt;
    
                                            }
    
                                            // TIME
                                            Event::find($event->id)->update(['order_overall' => $order_overall++]);

                                            $eventCurrent = 4;
    
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

