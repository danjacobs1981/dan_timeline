<div class="event" data-id="{{ $event->id }}">
    <div class="details">
        <span>
            {{ $event->title }}
        </span>
        <ul class="options">
            <li>
                <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id, 'section' => 'general' ]) }}" class="link" data-modal data-modal-class="modal-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                    Update Event Details
                </a>
            </li>
            <li>
                <a href="{{ route('timelines.events.edit.date', [ 'timeline' => $event->timeline_id, 'event' => $event->id ]) }}" class="link" data-modal data-modal-class="modal-edit-event-date" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                    Change Date
                </a>
            </li>
        </ul>
    </div>
    <div class="handle">
        <i class="fa-solid fa-arrows-up-down"></i>
    </div>
</div>