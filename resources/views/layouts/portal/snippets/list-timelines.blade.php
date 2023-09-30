@push('scripts')
        @vite('resources/js/portal/timeline/ajax/settings.js')
        @vite('resources/js/portal/timeline/ajax/privacy.js')
@endpush

<ul>
    @foreach($timelines as $timeline)
        <li>
            {{ $timeline->title }} 
            <ul>
                <li>
                    <a href="{{ route('timelines.edit', ['timeline' => $timeline->id ]) }}">Edit</a>
                </li>
                <li>
                    <a href="{{ route('timeline.show', ['timeline' => $timeline->id,'slug' => $timeline->slug ]) }}">View</a>
                </li>
                <li>
                    Privacy: {{ $timeline->privacy }} <a href="{{ route('timeline.privacy.showModal', [ 'timeline' => $timeline->id ]) }}" data-modal data-modal-size="modal-lg">Change</a>
                </li>
            </ul> 
        </li>  
    @endforeach
</ul>