<div class="timelineSuggestion">
    <p>
        Suggest to the timeline creator to make an edit.
    </p>

    <input type="hidden" name="timeline_id" value="{{ $timeline->id }}" />

    @if($event->id)
        <div class="control control--radio">
            <span class="control__label">Make an edit suggestion for</span>
            <label class="control__label">The whole timeline
                <input type="radio" value="0" name="event_id" />
                <div></div>
            </label>
            <label class="control__label">This specific event: <strong>"{{ $event->title }}"</strong>
                <input type="radio" value="{{ $event->id }}" name="event_id" id="event_id" checked />
                <div></div>
            </label>
        </div>
    @endif

    <div class="control control--checkbox">
        <label class="control__label" for="anonymous">Hide my user name (make me anonymous) 
            <input type="hidden" name="anonymous" value="0">
            <input type="checkbox" name="anonymous" id="anonymous" value="1">
            <div></div>
        </label>
        <p>Making your suggestion anonymous makes it unable for you to receive any feedback from the creator.</p>
    </div>

    <div class="control control--textarea">
        <label class="control__label" for="comments">Your edit suggestion</label>
        <textarea id="comments" name="comments" maxlength="1000" rows="6" cols="50"></textarea>
        <p>Please use respectful communication with positive language.</p>
    </div>
</div>

@isset($modal)
    @vite('resources/css/resource/form.scss')
    @vite('resources/js/timeline/suggestion.js')
@else
    @push('stylesheets')
        @vite('resources/css/resource/form.scss')
    @endpush
    @push('scripts')
        @vite('resources/js/timeline/suggestion.js')
    @endpush
@endif