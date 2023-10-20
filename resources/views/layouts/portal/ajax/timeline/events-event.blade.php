<div class="event" data-id="{{ $event->id }}">
    <div class="details">
        <div>
            <div>
                {!! $date !!}
            </div>
            <a class="change-date" href="{{ route('timelines.events.edit.date', [ 'timeline' => $event->timeline_id, 'event' => $event->id ]) }}" data-modal data-modal-class="modal-edit-event-date" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">Change Date</a><br/>
        </div>
        <p>
            {{ $event->title }}
        </p>
        <ul class="options">
            <li>
                <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id, 'section' => 'general' ]) }}" class="link" data-modal data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">
                    Edit Event
                </a>
            </li>
            <li>
                <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id, 'section' => 'general' ]) }}" class="filled" data-modal data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">
                    Details
                </a>
            </li>
            <li>
                <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id, 'section' => 'map' ]) }}" data-modal data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">
                    Map
                </a>
            </li>
            <li>
                <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id, 'section' => 'resources' ]) }}" data-modal data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">
                    Resources
                </a>
            </li>
            <li>
                <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id, 'section' => 'tags' ]) }}" data-modal data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">
                    Tags
                </a>
            </li>
            <li>
                <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id, 'section' => 'comments' ]) }}" data-modal data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">
                    Comments
                </a>
            </li>
        </ul>
    </div>
    <div class="handle" title="Drag to reorder">
        <i class="fa-solid fa-arrows-up-down"></i>
    </div>
</div>