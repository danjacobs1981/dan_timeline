<h6>{{ $event->title }}</h6>

@if(!$previous)
    {{ $event->date_year }} {{ $event->date_month }} {{ $event->date_day }} {{ $event->date_time }}
    <input type="hidden" value="0" id="previous">
@else
    <div>
        Current: {{ $event->date_year }} {{ $event->date_month }} {{ $event->date_day }} {{ $event->date_time }}<br/>
        To: {{ $previous->date_year }} {{ $previous->date_month }} {{ $previous->date_day }} {{ $previous->date_time }}
    </div>
    <input type="hidden" value="1" id="previous">
@endif

@isset($modal)
    @vite('resources/js/portal/timeline/event/edit-date.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/event/edit-date.js')
    @endpush
@endif