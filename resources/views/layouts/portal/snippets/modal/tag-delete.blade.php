<div id="tagDelete">
    <h6>Are you sure?</h6>
    <p>This will delete this tag: <strong>{{ $tag->tag }}</strong>.</p>
    <p>This tag features on <strong>{{ $tag->events()->count() == 1 ? '1 event' : $tag->events()->count().' events' }}</strong>.</p>
    <p>This action cannot be undone.</p>
    <input type="hidden" name="timeline_id" value="{{ $timeline->id }}">
    <input type="hidden" name="tag_id" value="{{ $tag->id }}">
</div>

@isset($modal)
    @vite('resources/js/portal/timeline/tag/delete.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/tag/delete.js')
    @endpush
@endif