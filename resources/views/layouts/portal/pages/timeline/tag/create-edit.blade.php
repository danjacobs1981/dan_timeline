@if(isset($tag))
    <meta name="tag" content="{{ $tag->id }}">
@endif

<div id="timelineTagCreateEdit">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formTagCreateEdit" method="post" action="{{ isset($tag) ? route('timelines.tags.update', [ 'timeline' => $timeline, 'tag' => $tag ]) : route('timelines.tags.store', [ 'timeline' => $timeline ]) }}">

        @if(isset($tag))
            @method('put')
        @endif

        @csrf

        @if(isset($tag))
            <p>
                This tag currently features on <strong>{{ $tag->events()->count() == 1 ? '1 event' : $tag->events()->count().' events' }}</strong>.
            </p>
        @endif

        <div class="control control--textbox">
            <label class="control__label" for="tag">
                Title
            </label>
            <input type="text" name="tag" maxlength="40" id="tag" value="{{ old('tag', isset($tag) ? $tag->tag : '') }}"/>
            <p>
                Alphanumeric characters only.
            </p>
        </div>

        @include('layouts.portal.snippets.premium', [ 'title' => 'Tag Groups & Icons/Images', 'message' => 'Become a premium member and categorise your tags into groups to create a better filtering experience. Also, add images or icons to individual tags.' ])

        @if (auth()->user()->premium)
            <div class="control control--select">
                <label class="control__label" for="group_id">Group</label>
                <select name="group_id" id="group_id">
                    <option value="">Select group...</option>
                    @foreach ($timeline->groups->sortBy('group', SORT_NATURAL|SORT_FLAG_CASE) as $group)
                        <option value="{{ $group->id }}" {{ $group->id == $tag->group_id ? 'selected' : '' }}>{{ $group->group }}</option>
                    @endforeach
                    <option value="">Other (unassigned)</option>
                </select>
                <p>
                    <a href="{{ route('timelines.groups.create', [ 'timeline' => $timeline->id ]) }}" data-modal data-modal-class="modal-create-edit-group" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false">
                        Create New Group
                    </a>
                </p>
            </div>
        @endif

    </form>

    @if(isset($tag))
        <div class="control">
            <span class="control__label">
                Delete Tag
            </span>
            <a href="{{ route('timelines.tags.delete.showModal', [ 'timeline' => $timeline->id, 'tag' => $tag->id ]) }}" class="btn btn-danger" data-modal data-modal-class="modal-timeline-tag-delete modal-delete" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">
                <i class="fa-regular fa-trash-can"></i>Delete
            </a>                            
        </div>
    @endif

</div>

@isset($modal)
    @vite('resources/css/portal/timeline/tag/create-edit.scss')
    @vite('resources/js/portal/timeline/tag/create-edit.js')
@else
    @push('stylesheets')
        @vite('resources/css/portal/timeline/tag/create-edit.scss')
    @endpush
    @push('scripts')
        @vite('resources/js/portal/timeline/tag/create-edit.js')
    @endpush
@endif
