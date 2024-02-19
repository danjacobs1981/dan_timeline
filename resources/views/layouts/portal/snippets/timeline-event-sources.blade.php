<div class="{{ $placement }}Sources">

    @if($placement == 'timeline')
        <p>Listing of all sources that can be added to individual events to give them more clarity and weight.</p>
        <div class="control control--textbox">
            <label class="control__label" for="timelineSourceURL">Create New Source</label>
            <div class="control__multiple">
                <input type="text" id="timelineSourceURL" placeholder="Enter the URL of the source" />
                <a href="{{ route('timelines.sources.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-scroll data-modal-class="modal-create-edit-source" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false">
                    <i class="fa-solid fa-circle-plus"></i>Create Source
                </a>
            </div>
            <p>
                e.g: https://example.com/article
            </p>
            <p>
                This can be a URL of a webpage, image, PDF, YouTube video, etc.
            </p>
        </div>
    @elseif($placement == 'event')
        <p>Select sources to feature on the event. Useful and relevant sources can give the event more clarity and weight.</p>
        <a href="{{ route('timelines.sources.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-scroll data-modal-class="modal-create-edit-source" data-modal-size="modal-md" data-modal-showclose="false" data-modal-clickclose="false">
            <i class="fa-solid fa-circle-plus"></i>Create Source
        </a>
        <input type="hidden" name="sources_changed" value="0" />
    @endif

    <div class="control control--list control--checkbox">
        <div class="control__header">
            <div>
                @if($placement == 'timeline')
                    <span class="control__label">All Sources</span>
                @elseif($placement == 'event')
                    <span class="control__label">Select sources to add to the event</span>
                @endif
                <p class="sources-intro">Loading...</p>        
            </div>
            <div class="control_sort">
                <label for="{{ $placement }}SourceSort">
                    Sort:
                </label>
                <select id="{{ $placement }}SourceSort">
                    <option value="az">A-Z</option>
                    <option value="za">Z-A</option>
                    <option value="added">Date Added</option>
                    <option value="modified">Date Modified</option>
                </select>
            </div>
        </div>
        <div class="loading sources-loading">
            <div class="dots"><div></div><div></div><div></div><div></div></div>
        </div>
        <div class="sources-list"></div>
    </div>

</div>