<div class="timelineReport">

    <p>
        If you find content on this timeline which you consider to be spam, harassment, or which violates <a href="/policy/rules" target="_blank" title="Timelined.net's rules">Timelined.net's rules</a>, please report it to us by completing this form.
    </p>

    <p>
        There is also the option to suggest to the timeline creator to make an edit. This may be a good first-port-of-call to get any issues you may have resolved. <a data-modal data-modal-scroll data-modal-class="{{ $event->id ? 'modal-suggestion modal-suggestion-event' : 'modal-suggestion'}}" data-modal-size="modal-md" data-modal-showclose="true" href="{{ route('timeline.suggestion.showModal', [ 'timeline' => $timeline->id, 'event' => $event->id ]) }}">Click here to suggest an edit</a>.
    </p>

    <input type="hidden" name="timeline_id" value="{{ $timeline->id }}" />

    @if($event->id)
        <div class="control control--radio">
            <span class="control__label">Report on</span>
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

    <div class="control control--radio">
        <span class="control__label">Reason</span>
        <label class="control__label">Misinformation
            <input type="radio" value="Misinformation" name="category" checked />
            <div></div>
        </label>
        <label class="control__label">Harassment
            <input type="radio" value="Harassment" name="category" />
            <div></div>
        </label>
        <label class="control__label">Rules Violation
            <input type="radio" value="Rules Violation" name="category" />
            <div></div>
        </label>
        <label class="control__label">Spam
            <input type="radio" value="Spam" name="category" />
            <div></div>
        </label>
        <label class="control__label">Other
            <input type="radio" value="Other" name="category" />
            <div></div>
        </label>
    </div>

    <div class="control control--textarea">
        <label class="control__label" for="comments">Comments</label>
        <textarea id="comments" name="comments" maxlength="1000" rows="6" cols="50"></textarea>
        <p>Provide any details on what you are reporting on.</p>
    </div>

</div>

@isset($modal)
    @vite('resources/css/resource/form.scss')
    @vite('resources/js/timeline/report.js')
@else
    @push('stylesheets')
        @vite('resources/css/resource/form.scss')
    @endpush
    @push('scripts')
        @vite('resources/js/timeline/report.js')
    @endpush
@endif
