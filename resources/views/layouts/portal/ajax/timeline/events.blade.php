@inject('carbon', 'Carbon\Carbon')
@php
    $eventNone = $eventType = null;
@endphp
@foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events)  
    @foreach ($events->unique('order_ny') as $event)
        @if($event->date_year === null)
            <section class="none">
                @if($loop->first && $eventNone)
                    <h2>
                        {{ $event->period }}
                    </h2>
                @endif
                {{ $event->title }}
                @php($eventType = null)
            </section>
            @php($eventNone = null)
        @else
            @php($eventNone = 1)
            <section class="year">
                @foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event)
                    @if($loop->first && $event->date_month === null)
                        <h2>
                            {{ $event->date_year }}
                        </h2>
                    @endif
                    @if($event->date_month === null)
                        <section>
                            @if($eventType != 1 && !$loop->first)
                                <h2>
                                    Later in {{ $event->date_year }}
                                </h2>
                            @endif
                            {{ $event->title }}
                            @php($eventType = 1)
                        </section>
                    @else
                    <section class="month">
                        @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event)
                            @if($loop->first && $event->date_day === null)
                                <?php $dt = $carbon::create()->year($event->date_year)->month($event->date_month) ?>
                                <h2>
                                    {{ $dt->format('F Y') }}
                                </h2>
                            @endif
                            @if($event->date_day === null)
                                <section>
                                    @if($eventType != 2 && !$loop->first)
                                        <h2>
                                            Later in {{ $dt->format('F') }}
                                        </h2>
                                    @endif
                                    {{ $event->title }}
                                    @php($eventType = 2)
                                </section>
                            @else
                            <section class="day">
                                @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event)
                                    @if($loop->first && $event->date_time === null)
                                        <?php $dt = $carbon::create()->year($event->date_year)->month($event->date_month)->day($event->date_day) ?>
                                        <h2>
                                            {{ $dt->format('l jS F Y') }}
                                        </h2>
                                    @endif
                                    @if($event->date_time === null)
                                        <section>
                                            @if($eventType != 3 && !$loop->first)
                                                <h2>
                                                    Later on {{ $dt->format('l jS') }}
                                                </h2>
                                            @endif
                                            {{ $event->title }}
                                            @php($eventType = 3)
                                        </section>
                                    @else
                                        <section class="time">
                                            @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_dt')->unique('order_dt') as $event)
                                                @if($loop->first)
                                                    <?php $dt = $carbon::createFromTimestamp($event->date_unix) ?>
                                                    <h2>
                                                        {{ $dt->format('h:ia l jS F Y') }}
                                                    </h2>
                                                @endif
                                                <section>
                                                    {{ $event->title }}
                                                    @php($eventType = 4)
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