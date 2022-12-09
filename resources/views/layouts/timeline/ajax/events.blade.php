@inject('carbon', 'Carbon\Carbon')
@php($eventNone = $eventType = null)
@foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events)  
    @foreach ($events->unique('order_ny') as $event)
        @if($event->date_year === null)
            <section class="none">
                @if($loop->first && $eventNone)
                    <h2 class="event-title" data-period="{{ $event->period_none }}">
                        <span class="none">{{ $event->period_none }}</span>
                    </h2>
                @endif
                @include('layouts.timeline.ajax.events-event')
                @php($eventType = null)
            </section>
            @php($eventNone = null)
        @else
            @php($eventNone = 1)
            <section class="year">
                @foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event)
                    @if($loop->first)
                        <h2 class="{{ $event->date_month === null ? 'event-title' : ''}}" data-period="{{ $event->period_year }}">
                            <span class="year">{{ $event->date_year }}</span>
                            <!--{{ $event->difference_year }}-->
                        </h2>
                    @endif
                    @if($event->date_month === null)
                        @if($eventType != 1 && !$loop->first)
                            <h2 class="event-title" data-period="Later in {{ $event->date_year }}">
                                <span class="none">Later in {{ $event->date_year }}</span>
                            </h2>
                        @endif
                        <section>
                            @include('layouts.timeline.ajax.events-event')
                            @php($eventType = 1)
                        </section>
                    @else
                    <section class="month">
                        @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event)
                            <?php $dt = $carbon::create()->year($event->date_year)->month($event->date_month) ?>
                            @if($loop->first)
                                <h2 class="{{ $event->date_day === null ? 'event-title' : ''}}" data-period="{{ $event->period_month }}">
                                    <span class="year">{{ $event->date_year }}</span>
                                    <span class="month">{{ $dt->format('F') }}</span>
                                    <!--{{ $event->difference_month }}-->
                                </h2>
                            @endif
                            @if($event->date_day === null)
                                @if($eventType != 2 && !$loop->first)
                                    <h2 class="event-title" data-period="Later in {{ $dt->format('F') }}">
                                        <span class="none">Later in {{ $dt->format('F') }}</span>
                                    </h2>
                                @endif
                                <section>
                                    @include('layouts.timeline.ajax.events-event')
                                    @php($eventType = 2)
                                </section>
                            @else
                            <section class="day">
                                @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event)
                                    @if($loop->first)
                                        <?php $dt = $carbon::create()->year($event->date_year)->month($event->date_month)->day($event->date_day) ?>
                                        <h2 class="{{ $event->date_time === null ? 'event-title' : ''}}" data-period="{{ $event->period_day }}">
                                            <span class="year">{{ $event->date_year }}</span>
                                            <span class="month">{{ $dt->format('F') }}</span>
                                            <span class="day">{{ $dt->format('l jS') }}</span>
                                            <!--{{ $event->difference_day }}-->
                                        </h2>
                                    @endif
                                    @if($event->date_time === null)
                                        @if($eventType != 3 && !$loop->first)
                                            <h2 class="event-title" data-period="Later on {{ $dt->format('l jS') }}">
                                                <span class="none">Later on {{ $dt->format('l jS') }}</span>
                                            </h2>
                                        @endif
                                        <section>
                                            @include('layouts.timeline.ajax.events-event')
                                            @php($eventType = 3)
                                        </section>
                                    @else
                                        <section class="time">
                                            @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_dt')->unique('order_dt') as $event)
                                                @if($loop->first)
                                                    <?php $dt = $carbon::createFromTimestamp($event->date_unix) ?>
                                                    <h2 class="event-title" data-period="{{ $event->period_time }}">
                                                        <span class="year">{{ $event->date_year }}</span>
                                                        <span class="month">{{ $dt->format('F') }}</span>
                                                        <span class="day">{{ $dt->format('l jS') }}</span>
                                                        <span class="time">{{ $dt->format('h:ia') }}</span>
                                                        <!--{{ $event->difference_time }}-->
                                                    </h2>
                                                    <!--<em>{{ str_replace('_', ' ', $event->location_tz) }}</em>-->
                                                @endif
                                                @include('layouts.timeline.ajax.events-event')
                                                @php($eventType = 4)
                                            @endforeach
                                        </section>
                                    @endif
                                @endforeach
                            </section>
                            @endif
                        @endforeach
                    </section>
                    @endif
                @endforeach
            </section>
        @endif
    @endforeach
@endforeach