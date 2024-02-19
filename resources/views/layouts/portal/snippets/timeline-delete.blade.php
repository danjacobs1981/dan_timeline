<div class="timelineDelete">

    <p>Delete your timeline.</p>

    <div class="control">
        <span class="control__label">Delete Timeline</span>
        <a href="{{ route('timelines.delete.showModal', [ 'timeline' => $timeline->id ]) }}" class="btn btn-danger" data-modal data-modal-scroll data-modal-class="modal-timeline-delete modal-delete" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">
            <i class="fa-regular fa-trash-can"></i>Delete
        </a>                            
        <p>Deleting a timeline cannot be undone.</p>
    </div>

</div>