<div id="sourceDelete">
    <h6>Are you sure?</h6>
    <p>This will delete this source: <strong>{{ $source->title }}</strong>.</p>
    <p>This source features on <strong>{{ $event_count == 1 ? '1 event' : $event_count.' events' }}</strong>.</p>
    <p>This action cannot be undone.</p>
    <input type="hidden" name="timeline_id" value="{{ $timeline->id }}">
    <input type="hidden" name="source_id" value="{{ $source->id }}">
</div>

@isset($modal)
    @vite('resources/js/portal/timeline/source/delete.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/source/delete.js')
    @endpush
@endif