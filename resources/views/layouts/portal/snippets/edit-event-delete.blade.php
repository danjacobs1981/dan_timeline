<div id="eventDelete">
    <h6>Are you sure?</h6>
    <p>This will delete this event: <strong>{{ $event->title }}</strong></p>
    <p>This action cannot be undone.</p>
    <input type="hidden" name="timeline_id" value="{{ $timeline->id }}">
    <input type="hidden" name="event_id" value="{{ $event->id }}">
</div>

@isset($modal)
    @vite('resources/js/portal/timeline/event/delete.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/event/delete.js')
    @endpush
@endif