<div id="timelinePrivacyShare">
    <p>Invite others to view your private timeline by adding their email addresses below. To view your private timeline, the invitee must be logged in to {{ Config::get('constants.website.name') }}</p>
    <input class="tagify-outside tagify-privacy-share" value='{{ $privateUsers }}' placeholder="Enter email address and hit enter">
    <input type="hidden" name="timeline-id" value="{{ $timeline->id }}">
</div>

@isset($modal)
    @vite('resources/js/portal/timeline/privacy-share.js')
@else
    @push('scripts')
        @vite('resources/js/portal/timeline/privacy-share.js')
    @endpush
@endif