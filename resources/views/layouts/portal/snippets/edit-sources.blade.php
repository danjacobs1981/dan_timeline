<div id="{{ $placement }}Sources">

    @if($placement == 'timeline')
        <p>Sources that can be added to individual events to give them more clarity and weight.</p>
    @elseif($placement == 'event')
        <p>Add sources to the event.</p>
        <div class="control control--list">
            <span class="control__label">Event Sources</span>
            <p class="sources-current-intro">There are <span>0</span> source(s) currently featured on this event.</p>
            <div class="sources-list-current">
                <ul>
                </ul>
            </div>
        </div>
        <input type="hidden" name="sources_changed" value="0" />
    @endif

    <div class="control control--textbox control--button">
        <label class="control__label" for="source">Create New Source</label>
        <div>
            <input type="text" id="{{ $placement }}SourceURL" placeholder="Enter the URL of the source" />
            <a href="{{ route('timelines.sources.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-class="modal-create-edit-source" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false">
                <i class="fa-solid fa-circle-plus"></i>Create Source
            </a>
        </div>
        <p>This can be a URL of a webpage, image, PDF, YouTube video, etc.</p>
    </div>

    <div class="control control--list">
        <span class="control__label">All Sources</span>
        <p class="sources-intro">Loading...</p>
        <div class="loading sources-loading">
            <div class="dots"><div></div><div></div><div></div><div></div></div>
        </div>
        <div class="sources-list"></div>
    </div>

</div>