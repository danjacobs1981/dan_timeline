<div data-id="{{ $event->id }}" style="border:1px solid #000;padding:0.5rem;margin-bottom:0.5rem;">
    <i class="fas fa-arrows-alt handle"></i>
    {{ $date }} <a class="change-date" href="{{ route('event.date.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id ]) }}" data-modal data-modal-class="modal-edit-date" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">Change</a><br/>
    {{ $event->title }}<br/>
    <a href="{{ route('timelines.events.edit', [ 'timeline' => $event->timeline_id, 'event' => $event->id ]) }}" data-modal data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">Edit Details</a>
</div>