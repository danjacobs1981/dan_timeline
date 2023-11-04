<h6>{{ $event->title }}</h6>

<p>Current: {{ $event->date_year }} {{ $event->date_month }} {{ $event->date_day }} {{ $event->date_time }}</p>


@isset($modal)
    @vite('resources/js/portal/timeline/event/edit-date.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/event/edit-date.js')
    @endpush
@endif