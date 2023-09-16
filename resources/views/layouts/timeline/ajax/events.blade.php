@inject('carbon', 'Carbon\Carbon')
@php
    $eventNone = $eventType = $eventTZ = null;
    $periodCount = 1;
@endphp
@foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events)  
    @foreach ($events->unique('order_ny') as $event)
        @if($event->date_year === null)
            <section class="none">
                @if($loop->first && $eventNone)
                    <div class="event-title" data-period="{{ $event->period_none }}" data-order="{{ $periodCount++ }}">
                        <h2>
                            <span class="none">{{ $event->period_none }}</span>
                        </h2>
                    </div>
                @endif
                @include('layouts.timeline.ajax.events-event')
                @php($eventType = null)
            </section>
            @php($eventNone = null)
        @else
            @php($eventNone = 1)
            <section class="year">
                @foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event)
                    @if($loop->first && $event->date_month === null)
                        <div class="{{ $event->date_month === null ? 'event-title' : ''}}" data-period="{{ $event->period_year }}" data-order="{{ $periodCount++ }}">
                            @if($event->difference)
                                <span>{{ $event->difference }}</span>
                            @endif
                            <h2>
                                <span class="year">{{ $event->date_year }}</span>
                            </h2>
                        </div>
                    @endif
                    @if($event->date_month === null)
                        <section>
                            @if($eventType != 1 && !$loop->first)
                                <div class="event-title" data-period="Later in {{ $event->date_year }}" data-order="{{ $periodCount++ }}">
                                    <h2>
                                        <span class="none">Later in {{ $event->date_year }}</span>
                                    </h2>
                                </div>
                            @endif
                            @include('layouts.timeline.ajax.events-event')
                            @php($eventType = 1)
                        </section>
                    @else
                    <section class="month">
                        @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event)
                            @if($loop->first && $event->date_day === null)
                                <?php $dt = $carbon::create()->year($event->date_year)->month($event->date_month) ?>
                                <div class="{{ $event->date_day === null ? 'event-title' : ''}}" data-period="{{ $event->period_month }}" data-order="{{ $periodCount++ }}">
                                    @if($event->difference)
                                        <span>{{ $event->difference }}</span>
                                    @endif
                                    <h2>
                                        <span class="month">{{ $dt->format('F') }}</span>
                                        <span class="year">{{ $event->date_year }}</span>
                                    </h2>
                                </div>
                            @endif
                            @if($event->date_day === null)
                                <section>
                                    @if($eventType != 2 && !$loop->first)
                                        <div class="event-title" data-period="Later in {{ $dt->format('F, Y') }}" data-order="{{ $periodCount++ }}">
                                            <h2>
                                                <span class="none">Later in {{ $dt->format('F') }}</span>
                                            </h2>
                                        </div>
                                    @endif
                                    @include('layouts.timeline.ajax.events-event')
                                    @php($eventType = 2)
                                </section>
                            @else
                            <section class="day">
                                @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event)
                                    @if($loop->first && $event->date_time === null)
                                        <?php $dt = $carbon::create()->year($event->date_year)->month($event->date_month)->day($event->date_day) ?>
                                        <div class="{{ $event->date_time === null ? 'event-title' : ''}}" data-period="{{ $event->period_day }}" data-order="{{ $periodCount++ }}">
                                            @if($event->difference)
                                                <span>{{ $event->difference }}</span>
                                            @endif
                                            <h2>
                                                <span class="day">{{ $dt->format('l jS') }}</span>
                                                <span class="month">{{ $dt->format('F') }}</span>
                                                <span class="year">{{ $event->date_year }}</span>
                                            </h2>
                                        </div>
                                    @endif
                                    @if($event->date_time === null)
                                        <section>
                                            @if($eventType != 3 && !$loop->first)
                                                <div class="event-title" data-period="Later on {{ $dt->format('l jS F, Y') }}" data-order="{{ $periodCount++ }}">
                                                    <h2>
                                                        <span class="none">Later on {{ $dt->format('l jS') }}</span>
                                                    </h2>
                                                </div>
                                            @endif
                                            @include('layouts.timeline.ajax.events-event')
                                            @php($eventType = 3)
                                        </section>
                                    @else
                                        <section class="time">
                                            @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_dt')->unique('order_dt') as $event)
                                                @if($loop->first)
                                                    <?php $dt = $carbon::createFromTimestamp($event->date_unix) ?>
                                                    <div class="event-title" data-period="{{ $event->period_time }}" data-order="{{ $periodCount++ }}">
                                                        @if($event->difference)
                                                            <span>{{ $event->difference }}</span>
                                                        @endif
                                                        <h2>
                                                            <span class="time">{{ $dt->format('h:ia') }}</span>
                                                            <span class="day">{{ $dt->format('l jS') }}</span>
                                                            <span class="month">{{ $dt->format('F') }}</span>
                                                            <span class="year">{{ $event->date_year }}</span>
                                                        </h2>
                                                        @if($event->location_tz && $event->location_tz != $eventTZ)
                                                            <em>Timezone: {{ $event->location_tz }}</em>
                                                        @endif
                                                    </div>
                                                    <!--<em>{{ str_replace('_', ' ', $event->location_tz) }}</em>-->
                                                @endif
                                                <section>
                                                    @include('layouts.timeline.ajax.events-event')
                                                    @php($eventType = 4)
                                                    @php($eventTZ = $event->location_tz)
                                                </section>
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