<div class="{{ $placement }}Tags">

    @if($placement == 'timeline')
        <p>Listing of all tags that can be added to individual events for filtering.</p>
    @elseif($placement == 'event')
        <p>Add tags to the event.</p>
        <input type="hidden" name="tags_changed" value="0" />
    @endif

    <div class="control control--textbox">
        <label class="control__label" for="{{ $placement }}_tag">
            Create New Tag
        </label>
        @if (auth()->user()->premium)
            <span class="premium"><i class="fa-solid fa-crown"></i>Premium Feature</span>
        @endif
        <div class="control__multiple">
            <input type="text" data-name="tag" name="{{ $placement }}_tag" maxlength="40" id="{{ $placement }}_tag" placeholder="Tag title" />
            @if (auth()->user()->premium)
                <select name="{{ $placement }}_group_id" id="{{ $placement }}_group_id">
                    <option value="">Select group...</option>
                    @foreach ($timeline->groups->sortBy('group', SORT_NATURAL|SORT_FLAG_CASE) as $group)
                        <option value="{{ $group->id }}">{{ $group->group }}</option>
                    @endforeach
                    <option value="">Other (unassigned)</option>
                </select>
            @endif
            <button type="button" class="btn btn-outline" data-id="{{ $timeline->id }}">
                <i class="fa-solid fa-circle-plus"></i>Create Tag
            </button>
        </div>
        <p>
            Alphanumeric characters only.
        </p>
        @if (auth()->user()->premium)
            <p>
                <a href="{{ route('timelines.groups.create', [ 'timeline' => $timeline->id ]) }}" data-modal data-modal-class="modal-create-edit-group" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false">
                    Create New Group
                </a>
            </p>
        @endif
    </div>

    @include('layouts.portal.snippets.premium', [ 'title' => 'Tag Groups & Icons/Images', 'message' => 'Become a premium member and categorise your tags into groups to create a better filtering experience. Also, add images or icons to individual tags.' ])

    <div class="control control--list control--checkbox">
        <div class="control__header">
            <div>
                @if($placement == 'timeline')
                    <span class="control__label">
                        All Tags
                    </span>
                @elseif($placement == 'event')
                    <span class="control__label">
                        Select tags to add them to the event
                    </span>
                @endif
                <p class="tags-intro">Loading...</p>        
            </div>
            <div class="control_sort">
                <label for="{{ $placement }}TagSort">
                    Sort:
                </label>
                <select id="{{ $placement }}TagSort">
                    <option value="az">A-Z</option>
                    <option value="za">Z-A</option>
                    <option value="added">Date Added</option>
                    <option value="modified">Date Modified</option>
                </select>
            </div>
        </div>
        <div class="loading tags-loading">
            <div class="dots"><div></div><div></div><div></div><div></div></div>
        </div>
        <div class="tags-list"></div>
    </div>

</div>