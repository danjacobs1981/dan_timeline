<div id="timelineEventEditLocation">

    <h6>Event: {{ $event->title }}</h6>

    <p>this will pass location details to the date modal</p>

</div>

@isset($modal)
    @vite('resources/js/portal/timeline/event/edit-location.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/event/edit-location.js')
    @endpush
@endif