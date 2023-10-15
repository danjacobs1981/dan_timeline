<div id="timelinePrivacy">

    @if(old('privacy', $timeline->privacy) === 0)
        <span class="privacy-draft">
            <i class="fa-solid fa-circle-exclamation"></i> Timeline is currently a draft
        </span>
    @endif

    <div>
        <div class="control control--radio">
            <label class="control__label">Public
                <input data-id="{{ $timeline->id }}" type="radio" id="public" name="privacy" value="3" {{ old('privacy', $timeline->privacy) === 3 ? 'checked' : '' }}>
                <div></div>
            </label>
        </div>
        <p>
            Everyone can see your timeline.
        </p>
    </div>
    
    <div>
        <div class="control control--radio">
            <label class="control__label">Unlisted
                <input data-id="{{ $timeline->id }}" type="radio" id="unlisted" name="privacy" value="2" {{ old('privacy', $timeline->privacy) === 2 ? 'checked' : '' }}>
                <div></div>
            </label>
        </div>
        <p>
            Anyone with the timeline link can see your timeline.
        </p>
    </div>

    <div>
        <div class="control control--radio">
            <label class="control__label">Private
                <input data-id="{{ $timeline->id }}" type="radio" id="private" name="privacy" value="1" {{ old('privacy', $timeline->privacy) === 1 ? 'checked' : '' }}>
                <div></div>
            </label>
        </div>
        <p>
            Only you, your collaborators, and selected people can see your timeline.
        </p>
        <div class="privacy-share" style="{{ old('privacy', $timeline->privacy) === 1 ? '' : 'display:none;' }}">
            <p>
                @if($privateUsers->count() === 0)
                    Select people to view your private timeline.
                @elseif($privateUsers->count() > 1)
                    Privately shared with {{ $privateUsers->count() }} people.
                @else
                    Privately shared with {{ $privateUsers->first()->value }}
                @endif
            </p>
            <a class="btn" href="{{ route('timeline.privacy-share.showModal', [ 'timeline' => $timeline->id ]) }}" data-modal data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false" data-modal-class="modal-privacy-share">
                {{ $privateUsers->count() ? 'Edit' : 'Add' }}
            </a>
        </div>
    </div>

</div>

@isset($modal)
    @vite('resources/js/portal/timeline/privacy.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/privacy.js')
    @endpush
@endif
