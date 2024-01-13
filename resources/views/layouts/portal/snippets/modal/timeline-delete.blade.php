<div id="timelineDelete">
    <h6>Are you sure?</h6>
    <p>This will delete <strong>{{ $timeline->title }}</strong> and all {{ $timeline->events->count() }} events.</p>
    <p>This action cannot be undone.</p>
    <form id="formDelete" action="{{ route('timelines.destroy', $timeline->id) }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
</div>