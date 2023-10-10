<div id="timelineSettings">
    
    <div class="control control--textbox">
        <label class="control__label" for="title">Timeline Title</label>
        <input name="title" id="title" data-value="{{ old('title', $timeline->title) }}" value="{{ old('title', $timeline->title) }}">
        <p>The title should sum up your timeline in just a few words. This will also make up your timeline URL.</p>
    </div>

    @if (auth()->user()->premium)
        <div class="control control--checkbox">
            <label class="control__label" for="adverts">Show adverts
                <input type="hidden" name="adverts" value="0">
                <input type="checkbox" name="adverts" id="adverts" value="1" {{ old('adverts', $timeline->adverts) ? 'checked' : '' }}>
                <div></div>
            </label>
        </div>
    @else
        <div class="control control--checkbox">
            <label class="control__label">Remove adverts <a href="#" class="premium"><i class="fa-solid fa-crown"></i>Go Premium</a>
                <input type="checkbox" disabled="disabled">
                <div></div>
                <i class="fa-solid fa-lock"></i>
            </label>
            <p>Remove all advertising banners from the timeline event feed.</p>
        </div>
    @endif


    <div class="control control--checkbox">
        <label class="control__label" for="map">Show map
            <input type="hidden" name="map" value="0">
            <input type="checkbox" name="map" id="map" value="1" {{ old('map', $timeline->map) ? 'checked' : '' }}>
            <div></div>
        </label>
        <p>Hiding the map will also hide any event buttons that are referenced to it. If no events utilise the map then it is hidden by default.</p>
    </div>

    <div class="control control--checkbox">      
        <label class="control__label" for="comments">Show comments
            <input type="hidden" name="comments" value="0">
            <input type="checkbox" name="comments" id="comments" value="1" {{ old('comments', $timeline->comments) ? 'checked' : '' }}>
            <div></div>
        </label>
        <label class="control__label" for="comments_event">Show comments per event
            <input type="hidden" name="comments_event" value="0">
            <input type="checkbox" name="comments_event" id="comments_event" value="1" {{ old('comments_event', $timeline->comments_event) ? 'checked' : '' }}>
            <div></div>
        </label>
    </div>

    @if (auth()->user()->premium)
        <div class="control control--checkbox">
            <label class="control__label" for="filter">Use advanced <a href="#tags-tab" class="tab">tagging &amp; filtering</a>
                <input type="hidden" name="filter" value="0">
                <input type="checkbox" name="filter" id="filter" value="1" {{ old('filter', $timeline->filter) ? 'checked' : '' }}>
                <div></div>
            </label>
            <p>Group your tags to give your timeline more depth when filtering.</p>
        </div>    
    @else
        <div class="control control--checkbox">
            <label class="control__label">Use advanced <a href="#tags-tab" class="tab">tagging &amp; filtering</a> <a href="#" class="premium"><i class="fa-solid fa-crown"></i>Go Premium</a>
                <input type="checkbox" disabled="disabled">
                <div></div>
                <i class="fa-solid fa-lock"></i>
            </label>
            <p>Group your tags to give your timeline more depth when filtering.</p>
        </div>    
    @endif

    <div class="control control--checkbox">
        <label class="control__label" for="social">Show social sharing options
            <input type="hidden" name="social" value="0">
            <input type="checkbox" name="social" id="social" value="1" {{ old('social', $timeline->social) ? 'checked' : '' }}>
            <div></div>
        </label>
    </div>

    <div class="control control--checkbox">
        <label class="control__label" for="collab">Offer requests to collaborate
            <input type="hidden" name="collab" value="0">
            <input type="checkbox" name="collab" id="collab" value="1" {{ old('collab', $timeline->collab) ? 'checked' : '' }}>
            <div></div>
        </label>
        <p>Turning this off will have no affect on any of your exisiting collaborators, it simply removes the "Request to collaborate" buttons from your timeline.</p>
    </div>

    <div class="control control--checkbox">
        <label class="control__label" for="profile">List on profile
            <input type="hidden" name="profile" value="0">
            <input type="checkbox" name="profile" id="profile" value="1" {{ old('profile', $timeline->profile) ? 'checked' : '' }}>
            <div></div>
        </label>
        <p>Feature this timeline on your profile page.</p>
    </div>

    <!---
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
    --->

    <div class="control-submit">
        <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update Settings</button>
    </div>

</div>

@isset($modal)
    @vite('resources/js/portal/timeline/ajax/settings.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/ajax/settings.js')
    @endpush
@endif
