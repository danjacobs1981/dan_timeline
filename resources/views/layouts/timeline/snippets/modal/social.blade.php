@include('layouts.timeline.snippets.social', ['more'=>false])
<div class="form-input form-input-copy">
    <input type="text" readonly value="{{ request()->getSchemeAndHttpHost() }}/{{ $timeline->id }}/{{ $timeline->slug }}" />
    <div>checkbox: Share with current filters applied</div>
    <div>checkbox: Share at current event</div>
    <button>
        Copy
    </button>
</div>