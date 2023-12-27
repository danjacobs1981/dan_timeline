<ul>
    @foreach($timeline_sources->sortBy('title', SORT_NATURAL|SORT_FLAG_CASE) as $source)  
        <li class="source" data-id="{{ $source->id }}">
            <span>
                <i class="{{ $source->fa_icon }}"></i>
                <span>
                    <a href="{{ route('timelines.sources.edit', [ 'timeline' => $source->timeline_id, 'source' => $source->id ]) }}" data-modal data-modal-class="modal-create-edit-source" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false">
                        {{ $source->title }}
                    </a>
                </span>
            </span>
            <div>
                <a href="{{ $source->url }}" target="_blank" rel=”nofollow”>
                    <i class="fa-regular fa-window-restore"></i><span>View</span>
                </a>
                <a href="{{ route('timelines.sources.edit', [ 'timeline' => $source->timeline_id, 'source' => $source->id ]) }}" data-modal data-modal-class="modal-create-edit-source" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false">
                    <i class="fa-solid fa-pencil"></i><span>Edit</span>
                </a> 
            </div>
        </li>
    @endforeach
</ul>