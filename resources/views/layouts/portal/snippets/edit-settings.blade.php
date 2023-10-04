<div id="timelineSettings">
            <div>
                <label for="title">Title</label>
                <input name="title" id="title" data-value="{{ old('title', $timeline->title) }}" value="{{ old('title', $timeline->title) }}">
            </div>

            <div>
                <label for="comments">Show Comments?</label>
                <input type="hidden" name="comments" value="0">
                <input type="checkbox" name="comments" id="comments" value="1" {{ old('comments', $timeline->comments) ? 'checked' : '' }}>
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
