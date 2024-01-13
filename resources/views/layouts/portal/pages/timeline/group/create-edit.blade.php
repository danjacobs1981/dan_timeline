@if(isset($group))
    <meta name="group" content="{{ $group->id }}">
@endif

<div id="timelineGroupCreateEdit">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formGroupCreateEdit" method="post" action="{{ isset($group) ? route('timelines.groups.update', [ 'timeline' => $timeline, 'group' => $group ]) : route('timelines.groups.store', [ 'timeline' => $timeline ]) }}">

        @if(isset($group))
            @method('put')
        @endif

        @csrf

        @if(isset($group))
            <p>
                This group currently contains <strong>{{ $group->tags()->count() == 1 ? '1 tag' : $group->tags()->count().' tags' }}</strong>.
            </p>
        @else
        <p>
            Create a group that will contain tags.
        </p>
    @endif

        <div class="control control--textbox">
            <label class="control__label" for="group">
                Group Title
            </label>
            <input type="text" name="group" id="group" maxlength="50" value="{{ old('group', isset($group) ? $group->group : '') }}"/>
        </div>

    </form>

    @if(isset($group))
        <div class="control">
            <span class="control__label">
                Delete Group
            </span>
            <a href="{{ route('timelines.groups.delete.showModal', [ 'timeline' => $timeline->id, 'group' => $group->id ]) }}" class="btn btn-danger" data-modal data-modal-class="modal-timeline-group-delete modal-delete" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">
                <i class="fa-regular fa-trash-can"></i>Delete
            </a>                            
        </div>
    @endif

</div>

@isset($modal)
    @vite('resources/css/portal/timeline/group/create-edit.scss')
    @vite('resources/js/portal/timeline/group/create-edit.js')
@else
    @push('stylesheets')
        @vite('resources/css/portal/timeline/group/create-edit.scss')
    @endpush
    @push('scripts')
        @vite('resources/js/portal/timeline/group/create-edit.js')
    @endpush
@endif
