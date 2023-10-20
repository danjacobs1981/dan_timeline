@inject('carbon', 'Carbon\Carbon')

@php 
    $month = ['', "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
@endphp

@foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events)  
    @foreach ($events->unique('order_ny') as $event)
        @if($event->date_year === null)
            @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>(no date)</span>' ])
        @else
            @foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event)
                @if($event->date_month === null)
                    @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>'.$event->date_year.'</span>' ])
                @else
                    @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event)
                        @if($event->date_day === null)
                            @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>'.$event->date_year.'</span> <span>'.$month[$event->date_month].'</span>' ])
                        @else
                            @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event)
                                @if($event->date_time === null)
                                    @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>'.$event->date_year.'</span> <span>'.$month[$event->date_month].'</span> <span>'.$carbon::parse($event->date_unix)->format('jS (l)').'</span>' ])
                                @else
                                    @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_dt')->unique('order_dt') as $event)
                                        @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>'.$event->date_year.'</span> <span>'.$month[$event->date_month].'</span> <span>'.$carbon::parse($event->date_unix)->format('jS (l)').'</span> <span>'.$carbon::parse($event->date_time)->format('h:ia').'</span>' ])
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif
    @endforeach
@endforeach