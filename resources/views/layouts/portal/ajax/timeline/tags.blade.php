@if(auth()->user()->premium)
    @foreach($timeline_groups->sortBy('group', SORT_NATURAL|SORT_FLAG_CASE) as $group)
    <div class="tags-group">
        <strong>
            {{ $group->group }} <a href="{{ route('timelines.groups.edit', [ 'timeline' => $group->timeline_id, 'group' => $group->id ]) }}" data-modal data-modal-class="modal-create-edit-group" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false" data-popover="Edit group" data-popover-position="top"><i class="fa-solid fa-pencil"></i></a>
        </strong>
        <ul class="sortable-tags" data-id="{{ $group->id }}">
            @forelse($timeline_tags->where('group_id', $group->id) as $tag)  
                @include('layouts.portal.ajax.timeline.tags-tag')
            @empty
                <span>
                    (no tags in this group)
                </span>
            @endforelse
        </ul>
    </div>
    @endforeach
    <div class="tags-group">
        <strong>
            Other (unassigned)
        </strong>
        <ul class="sortable-tags" data-id="">
            @forelse($timeline_tags->where('group_id', null) as $tag) 
                @include('layouts.portal.ajax.timeline.tags-tag')
            @empty
                <span>
                    (no unassigned tags)
                </span>
            @endforelse
        </ul>
    </div>
@else
    <div class="tags-group">
        <ul>
            @foreach($timeline_tags as $tag) 
                @include('layouts.portal.ajax.timeline.tags-tag')
            @endforeach
        </ul>
    </div>
@endif