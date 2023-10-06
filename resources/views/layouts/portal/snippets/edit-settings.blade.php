<div id="timelineSettings">

    
            <div class="control control--textbox">
                <label for="title">Title</label>
                <input name="title" id="title" data-value="{{ old('title', $timeline->title) }}" value="{{ old('title', $timeline->title) }}">
            </div>

            <div class="control control--checkbox">      
                <label class="control__label" for="comments">Show Comments?
                    <input type="hidden" name="comments" value="0">
                    <input type="checkbox" name="comments" id="comments" value="1" {{ old('comments', $timeline->comments) ? 'checked' : '' }}>
                    <div></div>
                </label>
            </div>

            <div class="control control--checkbox">
                <label class="control__label" for="profile">Show on profile?
                    <input type="hidden" name="profile" value="0">
                    <input type="checkbox" name="profile" id="profile" value="1" {{ old('profile', $timeline->profile) ? 'checked' : '' }}>
                    <div></div>
                </label>
            </div>

            <div class="control control--checkbox">
                <label class="control__label">Disabled
                    <input type="checkbox" disabled="disabled"/>
                    <div></div>
                </label>
                <label class="control__label">Disabled (checked)
                    <input type="checkbox" disabled="disabled" checked="checked"/>
                    <div></div>
                </label>
            </div>

            <div class="control control--radio">
                <label class="control__label">First radio (checked)
                    <input type="radio" name="radio" checked="checked"/>
                    <div></div>
                </label>
                <label class="control__label">Second radio
                    <input type="radio" name="radio"/>
                    <div></div>
                </label>
                <label class="control__label">Disabled
                    <input type="radio" name="radio2" disabled="disabled"/>
                    <div></div>
                </label>
                <label class="control__label">Disabled (checked)
                    <input type="radio" name="radio2" disabled="disabled" checked="checked"/>
                    <div></div>
                </label>
              </div>

            <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update Settings</button>
        </div>

@isset($modal)
    @vite('resources/js/portal/timeline/ajax/settings.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/ajax/settings.js')
    @endpush
@endif
