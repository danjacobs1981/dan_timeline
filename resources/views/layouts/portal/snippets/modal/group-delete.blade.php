<div id="groupDelete">
    <h6>Are you sure?</h6>
    <p>This will delete this group: <strong>{{ $group->group }}</strong>.</p>
    @if($group->tags()->count())
        <p>This group contains <strong>{{ $group->tags()->count() == 1 ? '1 tag' : $group->tags()->count().' tags' }}</strong>.</p>
        <p>Tags in this group will be moved to the "Other (unassigned)" group once this group is deleted.</p>
    @else
        <p>This group doesn't contain any tags.</p>
    @endif
    <p>This action cannot be undone.</p>
    <input type="hidden" name="timeline_id" value="{{ $timeline->id }}">
    <input type="hidden" name="group_id" value="{{ $group->id }}">
</div>

@isset($modal)
    @vite('resources/js/portal/timeline/group/delete.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/group/delete.js')
    @endpush
@endif