
       @if(old('privacy', $timeline->privacy) === 0)
            <p class="privacy-draft"><em>currently a draft</em></p>
        @endif

        <input data-id="{{ $timeline->id }}" type="radio" id="public" name="privacy" value="3" {{ old('privacy', $timeline->privacy) === 3 ? 'checked' : '' }}>
        <label for="public">public</label>
        <p>Everyone can see your timeline</p>
        <input data-id="{{ $timeline->id }}" type="radio" id="unlisted" name="privacy" value="2" {{ old('privacy', $timeline->privacy) === 2 ? 'checked' : '' }}>
        <label for="unlisted">unlisted</label>
        <p>Anyone with the timeline link can see your timeline</p>
        <input data-id="{{ $timeline->id }}" type="radio" id="private" name="privacy" value="1" {{ old('privacy', $timeline->privacy) === 1 ? 'checked' : '' }}>
        <label for="private">private</label>
        <p>Only you, your collaborators, and selected people can see your timeline</p>
        <div class="privacy-share" style="{{ old('privacy', $timeline->privacy) === 1 ? '' : 'display:none;' }}">
            <a href="{{ route('timeline.privacy-share.showModal', [ 'timeline' => $timeline->id ]) }}" data-modal data-modal-size="modal-sm">select people who can view your timeline</a>
        </div>


