<div class="timelineSettings">

    <div class="control-submit control-submit-sticky">
        <button data-id="{{ $timeline->id }}" type="button" class="btn" disabled>Update Settings</button>
    </div>

    <p>Edit general timeline settings, including which features are shown on your timeline.</p>

    <div class="visibility">
        <span data-popover="Change timeline visibility" data-popover-position="bottom">
            <em>
                Timeline Visibility
            </em>
            <strong>
                @if(old('privacy', $timeline->privacy) === 3)
                    <i class="fa-regular fa-eye public"></i>Public
                @elseif(old('privacy', $timeline->privacy) === 2)
                    <i class="fa-regular fa-eye"></i>Unlisted
                @elseif(old('privacy', $timeline->privacy) === 1)
                    <i class="fa-regular fa-eye-slash"></i>Private
                @else
                    <i class="fa-brands fa-firstdraft"></i>Draft
                @endif
            </strong>
        </span>
        <div class="visibility-options">
            @include('layouts.portal.snippets.edit-privacy')
            <span>
                <a href="#">
                    Done
                </a>
            </span>
        </div>
    </div>

    <div class="control control--textbox">
        <label class="control__label" for="title">Timeline Title</label>
        <input name="title" id="title" maxlength="250" data-value="{{ old('title', $timeline->title) }}" value="{{ old('title', $timeline->title) }}">
        <p>The title should reflect your timeline in just a few words. This will also make up your timeline URL.</p>
    </div>

    <div class="split">

        @if (auth()->user()->premium)
            <div class="control control--checkbox">
                <span class="control__label">Adverts</span>
                <span class="premium"><i class="fa-solid fa-crown"></i>Premium Feature</span>
                <p>No adverts are shown on the timeline!</p>
                <p>In fact, as a premium member, no adverts are shown anywhere, on any timeline, across the whole website.</p>
            </div>
        @else
            <div class="control control--checkbox control--premium">
                <span class="control__label">Adverts</span>
                <a href="#" class="premium"><i class="fa-solid fa-crown"></i>Go Premium</a>
                <label class="control__label">Remove adverts
                    <input type="checkbox" disabled="disabled">
                    <div></div>
                    <i class="fa-solid fa-lock"></i>
                </label>
                <p>Remove all advertising banners from your timeline event feed.</p>
            </div>
        @endif

        <div class="control control--checkbox">
            <span class="control__label">Map</span>
            <label class="control__label" for="map">Show map
                <input type="hidden" name="map" value="0">
                <input type="checkbox" name="map" id="map" value="1" {{ old('map', $timeline->map) ? 'checked' : '' }}>
                <div></div>
            </label>
            <label class="control__label" for="map_satellite">Default to satellite view
                <input type="hidden" name="map_satellite" value="0">
                <input type="checkbox" name="map_satellite" id="map_satellite" value="1" {{ old('map_satellite', $timeline->map_satellite) ? 'checked' : '' }}>
                <div></div>
            </label>
            <p>Hiding the map will also hide any event buttons that are referenced to it. If no events utilise a map marker then it is hidden by default.</p>
        </div>

    </div>

    <div class="split">

        <div class="control control--checkbox">    
            <span class="control__label">Comments</span>
            <label class="control__label" for="comment">Show comments
                <input type="hidden" name="comment" value="0">
                <input type="checkbox" name="comment" id="comment" value="1" {{ old('comment', $timeline->comment) ? 'checked' : '' }}>
                <div></div>
            </label>
            <label class="control__label" for="comment_event">Show comments per event
                <input type="hidden" name="comment_event" value="0">
                <input type="checkbox" name="comment_event" id="comment_event" value="1" {{ old('comment_event', $timeline->comment_event) ? 'checked' : '' }}>
                <div></div>
            </label>
            <p>Turning this off has no affect on any <a href="#comments-tab" class="tab">existing comments</a>, it simply removes any access to view them from your timeline.</p>
        </div>

        @if (auth()->user()->premium)
            <div class="control control--checkbox">
                <span class="control__label">Advanced Tag Grouping</span>
                <span class="premium"><i class="fa-solid fa-crown"></i>Premium Feature</span>
                <p>As a premium member, advanced <a href="#tags-tab" class="tab">tag grouping</a> is enabled.</p>
            </div>    
        @else
            <div class="control control--checkbox control--premium">
                <span class="control__label">Advanced Tag Grouping</span>
                <a href="#" class="premium"><i class="fa-solid fa-crown"></i>Go Premium</a>
                <label class="control__label">Use advanced <a href="#tags-tab" class="tab">tagging &amp; filtering</a>
                    <input type="checkbox" disabled="disabled">
                    <div></div>
                    <i class="fa-solid fa-lock"></i>
                </label>
                <p>Group your tags to give your timeline more depth when filtering.</p>
            </div>    
        @endif

    </div>

    <div class="split">

        <div class="control control--checkbox">
            <span class="control__label">Social</span>
            <label class="control__label" for="social">Show social sharing options
                <input type="hidden" name="social" value="0">
                <input type="checkbox" name="social" id="social" value="1" {{ old('social', $timeline->social) ? 'checked' : '' }}>
                <div></div>
            </label>
        </div>

        <div class="control control--checkbox">
            <span class="control__label">Collaborators</span>
            <label class="control__label" for="collab">Offer requests to collaborate
                <input type="hidden" name="collab" value="0">
                <input type="checkbox" name="collab" id="collab" value="1" {{ old('collab', $timeline->collab) ? 'checked' : '' }}>
                <div></div>
            </label>
            <p>Turning this off has no affect on any of your <a href="#collaborators-tab" class="tab">existing collaborators</a>, it simply removes the "Request to collaborate" buttons from your timeline.</p>
        </div>

    </div>

    <div class="control control--checkbox">
        <span class="control__label">Profile</span>
        <label class="control__label" for="profile">List on profile
            <input type="hidden" name="profile" value="0">
            <input type="checkbox" name="profile" id="profile" value="1" {{ old('profile', $timeline->profile) ? 'checked' : '' }}>
            <div></div>
        </label>
        <p>Feature this timeline on your <a href="{{ route('profile.show', ['username' => auth()->user()->username ]) }}" target="_blank">profile</a> page (requires the timeline visibility to be public).</p>
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
    @vite('resources/js/portal/timeline/settings.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/settings.js')
    @endpush
@endif
