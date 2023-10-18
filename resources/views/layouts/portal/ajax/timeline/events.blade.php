@inject('carbon', 'Carbon\Carbon')

@foreach($timeline_events->sortBy('order_ny')->groupBy('order_ny') as $events)  
    @foreach ($events->unique('order_ny') as $event)
        @if($event->date_year === null)
            
                @include('layouts.portal.ajax.timeline.events-event', [ 'date' => null ])
            
        @else
            
                @foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event)
                    @if($event->date_month === null)
                        @include('layouts.portal.ajax.timeline.events-event', [ 'date' => $event->date_year ])
                    @else
                    
                        @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event)
                            @if($event->date_day === null)
                                @include('layouts.portal.ajax.timeline.events-event', [ 'date' => $carbon::create()->year($event->date_year)->month($event->date_month)->format('F Y') ])
                            @else
                            
                                @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event)
                                    @if($event->date_time === null)
                                        @include('layouts.portal.ajax.timeline.events-event', [ 'date' => $carbon::create()->year($event->date_year)->month($event->date_month)->day($event->date_day)->format('l jS F Y') ])
                                    @else
                                        
                                            @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->where('date_unix', $event->date_unix)->where('date_unix_gmt', $event->date_unix_gmt)->sortBy('order_dt')->unique('order_dt') as $event)
                                                @include('layouts.portal.ajax.timeline.events-event', [ 'date' => $carbon::createFromTimestamp($event->date_unix)->format('h:ia l jS F Y') ])
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