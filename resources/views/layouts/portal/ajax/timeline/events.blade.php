@inject('carbon', 'Carbon\Carbon')

@php 
    $prev_date_unix = null;
    $prev_date_unix_gmt = null;
@endphp

@foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events)  
    @foreach ($events->unique('order_ny') as $event)
        @if($event->date_year === null)
            @include('layouts.portal.ajax.timeline.events-event', [ 'local' => $event->order_ny ])
        @else
            @php
                $total = $events->where('date_year', $event->date_year)->unique('order_ym')->count();
            @endphp
            <details data-local="{{ $event->order_ny }}" class="year{{ $total > 1 ? ' sortable-events' : '' }}">
            @foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event)
                @if($loop->first)
                    <summary>
                        <i class="fa-regular fa-square-caret-right"></i>
                        <span>{{ $event->date_year }}</span> 
                        <em>({{ $total > 1 ? $total.' entries' : $total.' entry' }})</em>
                        <a href="{{ route('timelines.events.create', [ 'timeline' => $event->timeline_id, 'predate' => $event->date_year ]) }}" data-popover="Add Event" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                            <i class="fa-solid fa-circle-plus"></i>
                        </a>
                    </summary>
                @endif
                @if($event->date_month === null)
                    @include('layouts.portal.ajax.timeline.events-event', [ 'local' => $event->order_ym ])
                @else
                    @php
                        $total = $events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->unique('order_md')->count();
                    @endphp
                    <details data-local="{{ $event->order_ym }}" class="month{{ $total > 1 ? ' sortable-events' : '' }}">
                    @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event)
                        @if($loop->first)
                            <summary>
                                <i class="fa-regular fa-square-caret-right"></i>
                                <span>{{ $carbon::parse($event->date_unix)->format('F') }}</span>
                                <em>({{ $total > 1 ? $total.' entries' : $total.' entry' }})</em>
                                <a href="{{ route('timelines.events.create', [ 'timeline' => $event->timeline_id, 'predate' => $carbon::parse($event->date_unix)->format('Y|n') ]) }}" data-popover="Add Event" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                                    <i class="fa-solid fa-circle-plus"></i>
                                </a>
                            </summary>
                        @endif
                        @if($event->date_day === null)
                            @include('layouts.portal.ajax.timeline.events-event', [ 'local' => $event->order_md ])
                        @else
                            @php
                                $total = $events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->unique('order_dt')->count();
                            @endphp
                            <details data-local="{{ $event->order_md }}" class="day{{ $total > 1 ? ' sortable-events' : '' }}">
                            @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event)
                                @if($loop->first)
                                    <summary>
                                        <i class="fa-regular fa-square-caret-right"></i>
                                        <span>{{ $carbon::parse($event->date_unix)->format('jS (l)') }}</span>
                                        <em>({{ $total > 1 ? $total.' entries' : $total.' entry' }})</em>
                                        <a href="{{ route('timelines.events.create', [ 'timeline' => $event->timeline_id, 'predate' => $carbon::parse($event->date_unix)->format('Y|n|j') ]) }}" data-popover="Add Event" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                                            <i class="fa-solid fa-circle-plus"></i>
                                        </a>
                                    </summary>
                                @endif
                                @if($event->date_time === null)
                                    @include('layouts.portal.ajax.timeline.events-event', [ 'local' => $event->order_dt ])
                                @else
                                    @php
                                        $total = $events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->unique('order_t')->count();
                                    @endphp
                                    <details data-local="{{ $event->order_dt }}" class="time{{ $total > 1 ? ' sortable-events' : '' }}">
                                        @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_t')->unique('order_t') as $event)
                                            @if($loop->first)
                                                <summary>
                                                    <i class="fa-regular fa-square-caret-right"></i>
                                                    <span>{{ $carbon::parse($event->date_time)->format('h:ia') }} <em>({{ $event->location_tz }})</em></span>
                                                    <em>({{ $total > 1 ? $total.' entries' : $total.' entry' }})</em>
                                                    <a href="{{ route('timelines.events.create', [ 'timeline' => $event->timeline_id, 'predate' => $carbon::parse($event->date_unix)->format('Y|n|j|h|i|a') ]) }}" data-popover="Add Event" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                                                        <i class="fa-solid fa-circle-plus"></i>
                                                    </a>
                                                </summary>
                                            @endif
                                            @include('layouts.portal.ajax.timeline.events-event', [ 'local' => $event->order_t ])
                                        @endforeach
                                    </details>
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