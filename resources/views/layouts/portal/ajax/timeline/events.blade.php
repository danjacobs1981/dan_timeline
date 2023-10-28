@inject('carbon', 'Carbon\Carbon')

@php 
    $month = ['', "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    $prev_date_unix = null;
    $prev_date_unix_gmt = null;
@endphp

@foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events)  
    @foreach ($events->unique('order_ny') as $event)
        @if($event->date_year === null)
            @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>(no date)</span>' ])
        @else
            @php
                $total = $events->where('date_year', $event->date_year)->unique('order_ym')->count();
            @endphp
            <details class="year{{ $total > 1 ? ' sortable' : '' }}">
            @foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event)
                @if($loop->first)
                    <summary>
                        <i class="fa-regular fa-square-caret-down"></i>
                        <span>{{ $event->date_year }}</span> 
                        <em>({{ $total > 1 ? $total.' entries' : $total.' entry' }})</em>
                        <a href="#" data-popover="Add Event"><i class="fa-solid fa-circle-plus"></i></a>
                    </summary>
                @endif
                @if($event->date_month === null)
                    @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>'.$event->date_year.'</span>' ])
                @else
                    @php
                        $total = $events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->unique('order_md')->count();
                    @endphp
                    <details class="month{{ $total > 1 ? ' sortable' : '' }}">
                    @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event)
                        @if($loop->first)
                            <summary>
                                <i class="fa-regular fa-square-caret-down"></i>
                                <span>{{ $month[$event->date_month] }}</span>
                                <em>({{ $total > 1 ? $total.' entries' : $total.' entry' }})</em>
                                <a href="#" data-popover="Add Event"><i class="fa-solid fa-circle-plus"></i></a>
                            </summary>
                        @endif
                        @if($event->date_day === null)
                            @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>'.$month[$event->date_month].'</span> <span>'.$event->date_year.'</span>' ])
                        @else
                            @php
                                $total = $events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->unique('order_dt')->count();
                            @endphp
                            <details class="day{{ $total > 1 ? ' sortable' : '' }}">
                            @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event)
                                @if($loop->first)
                                    <summary>
                                        <i class="fa-regular fa-square-caret-down"></i>
                                        <span>{{ $carbon::parse($event->date_unix)->format('jS (l)') }}</span>
                                        <em>({{ $total > 1 ? $total.' entries' : $total.' entry' }})</em>
                                        <a href="#" data-popover="Add Event"><i class="fa-solid fa-circle-plus"></i></a>
                                    </summary>
                                @endif
                                @if($event->date_time === null)
                                    @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>'.$carbon::parse($event->date_unix)->format('jS (D)').'</span> <span>'.$month[$event->date_month].'</span> <span>'.$event->date_year.'</span>' ])
                                @else
                                    @if($prev_date_unix != $event->date_unix || $prev_date_unix_gmt != $event->date_unix_gmt)
                                        @php
                                            $total = $events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->unique('order_dt')->count();
                                        @endphp
                                        <details class="time{{ $total > 1 ? ' sortable' : '' }}">
                                        @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_dt')->unique('order_dt') as $event)
                                            @if($loop->first)
                                                <summary>
                                                    <i class="fa-regular fa-square-caret-down"></i>
                                                    <span>{{ $carbon::parse($event->date_time)->format('h:ia') }} <em>({{ $event->location_tz }})</em></span>
                                                    <em>({{ $total > 1 ? $total.' entries' : $total.' entry' }})</em>
                                                    <a href="#" data-popover="Add Event"><i class="fa-solid fa-circle-plus"></i></a>
                                                </summary>
                                            @endif
                                            @include('layouts.portal.ajax.timeline.events-event', [ 'date' => '<span>'.$carbon::parse($event->date_time)->format('h:ia').'</span> <span>'.$carbon::parse($event->date_unix)->format('jS (D)').'</span> <span>'.$month[$event->date_month].'</span> <span>'.$event->date_year.'</span>' ])
                                        @endforeach
                                        </details>
                                    @endif
                                    @php
                                        $prev_date_unix = $event->date_unix;
                                        $prev_date_unix_gmt = $event->date_unix_gmt;
                                    @endphp
                                @endif
                            @endforeach
                            </details>
                        @endif
                    @endforeach
                    </details>
                @endif
            @endforeach
            </details>
        @endif
    @endforeach
@endforeach