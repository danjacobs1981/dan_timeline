@extends('layouts.portal.master')
@inject('carbon', 'Carbon\Carbon')

@section('content')

  @if(session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}  
    </div><br />
  @endif


        @foreach($all_events as $key => $events)  

            @foreach ($events->unique('order_ny') as $event)

                @if($event->date_year === null)

                    <div style="padding: 10px; border:solid 1px red; margin: 10px 10px 20px;">none event</div>

                @else
                    
                    <section style="padding: 10px; border:solid 1px red; margin: 10px 10px 20px;">

                        <h3>year: {{ $event->date_year }}</h3>

                         @foreach ($events->where('date_year', $event->date_year)->sortBy('order_ym')->unique('order_ym') as $event)

                            @if($event->date_month === null)

                                <div style="padding: 10px; border:solid 1px orange; margin: 10px 10px 20px;">just {{ $event->date_year }} (no month set) event</div>

                            @else

                            <section style="padding: 10px; border:solid 1px orange; margin: 10px 10px 20px;">

                                <h4>month: {{ $event->date_month }}</h4>

                                @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->sortBy('order_md')->unique('order_md') as $event)

                                    @if($event->date_day === null)

                                        <div style="padding: 10px; border:solid 1px green; margin: 10px 10px 20px;">just {{ $event->date_month }} (no day set) event</div>

                                    @else

                                    <section style="padding: 10px; border:solid 1px green; margin: 10px 10px 20px;">

                                        <h5>day: {{ $event->date_day }}</h5>

                                        @foreach ($events->where('date_year', $event->date_year)->where('date_month', $event->date_month)->where('date_day', $event->date_day)->sortBy('order_dt')->unique('order_dt') as $event)

                                            @if($event->date_time === null)

                                                <div style="padding: 10px; border:solid 1px blue; margin: 10px 10px 20px;">just {{ $event->date_day }} (no time set) event</div>

                                            @else

                                            

                                                <div style="padding: 10px; border:solid 1px blue; margin: 10px 10px 20px;">time: {{ $event->date_time }} ({{ $event->location_geo }} - {{ $event->location_tz }})</div>


                                            

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

@endsection