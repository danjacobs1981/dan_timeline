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

        <div class="control">
            <span class="control__label">
                Appearance
            </span>
            <ul class="tags-appearance">
                <li>
                    <label for="silver">
                        <input type="radio" name="color" id="silver" value="silver" {{ old('color', $tag->color == 'silver') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-silver">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="green">
                        <input type="radio" name="color" id="green" value="green" {{ old('color', $tag->color == 'green') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-green">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="red">
                        <input type="radio" name="color" id="red" value="red" {{ old('color', $tag->color == 'red') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-red">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="orange">
                        <input type="radio" name="color" id="orange" value="orange" {{ old('color', $tag->color == 'orange') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-orange">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="fuchsia">
                        <input type="radio" name="color" id="fuchsia" value="fuchsia" {{ old('color', $tag->color == 'fuchsia') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-fuchsia">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="purple">
                        <input type="radio" name="color" id="purple" value="purple" {{ old('color', $tag->color == 'purple') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-purple">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="maroon">
                        <input type="radio" name="color" id="maroon" value="maroon" {{ old('color', $tag->color == 'maroon') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-maroon">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="olive">
                        <input type="radio" name="color" id="olive" value="olive" {{ old('color', $tag->color == 'olive') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-olive">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="blue">
                        <input type="radio" name="color" id="blue" value="blue" {{ old('color', $tag->color == 'blue') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-blue">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="navy">
                        <input type="radio" name="color" id="navy" value="navy" {{ old('color', $tag->color == 'navy') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-navy">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="teal">
                        <input type="radio" name="color" id="teal" value="teal" {{ old('color', $tag->color == 'teal') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-teal">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="gray">
                        <input type="radio" name="color" id="gray" value="gray" {{ old('color', $tag->color == 'gray') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-gray">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
                <li>
                    <label for="black">
                        <input type="radio" name="color" id="black" value="black" {{ old('color', $tag->color == 'black') ? 'checked' : '' }}>
                        <span>
                            <span class="tag tag-black">{{ $tag->tag }}</span>
                        </span>
                    </label>
                </li>
            </ul>
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
