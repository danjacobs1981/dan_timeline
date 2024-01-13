<li class="tag" data-id="{{ $tag->id }}">
    <span>
        {{ $tag->tag }}
    </span>
    <a href="{{ route('timelines.tags.edit', [ 'timeline' => $tag->timeline_id, 'tag' => $tag->id ]) }}" data-modal data-modal-class="modal-create-edit-tag" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false" data-popover="Edit tag" data-popover-position="top">
        <i class="fa-solid fa-pencil"></i>
    </a>
    @if (auth()->user()->premium)
        <div class="handle">
            <i class="fa-solid fa-arrows-up-down"></i>
        </div> 
    @endif
</li>