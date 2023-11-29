<div class="event" data-id="{{ $event->id }}" data-local="{{ $local }}">
    <div class="details">
        <span>
            {{ $event->title }}
        </span>
        <ul>
            <li>
                <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id ]) }}" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                    <i class="fa-solid fa-pencil"></i>Edit <span>Event</span>
                </a>
            </li>
            <li>
                <a href="{{ route('timelines.events.edit.date', [ 'timeline' => $event->timeline_id, 'event' => $event->id ]) }}" data-modal data-modal-full data-modal-scroll data-modal-class="modal-edit-event-date" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                    <i class="fa-regular fa-calendar-days"></i><span>Quick</span> Change Date
                </a>
            </li>
        </ul>
    </div>
    <div class="handle">
        <i class="fa-solid fa-arrows-up-down"></i>
    </div>
</div>