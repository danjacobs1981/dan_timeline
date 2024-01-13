<div class="timelineEvents">

    <div class="loading">
        <div class="dots"><div></div><div></div><div></div><div></div></div>
    </div>

    <header>
        <div>
            <span>Loading events...</span>
            <em><i class="fa-regular fa-square-caret-right"></i>Expand all dates</li></em>
        </div>
        <a href="{{ route('timelines.events.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
            <i class="fa-solid fa-circle-plus"></i>Add Event
        </a>
    </header>

    <div class="control-submit">
        <a href="{{ route('timelines.events.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
            <i class="fa-solid fa-circle-plus"></i>Add Event
        </a>
    </div>
    
    <section id="events" class="sortable-events scrollbar"></section>

</div>
