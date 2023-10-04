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
                    Privacy: {{ $timeline->privacy }} <a href="{{ route('timeline.privacy.showModal', [ 'timeline' => $timeline->id ]) }}" data-modal data-modal-showclose="false" data-modal-clickclose="false" data-modal-class="modal-privacy">Change</a>
                </li>
            </ul> 
        </li>  
    @endforeach
</ul>