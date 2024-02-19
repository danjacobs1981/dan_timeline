@if(count(auth()->user()->timelines))
    <ul>
        @foreach(auth()->user()->timelines as $timeline)
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
                        <a href="{{ route('timelines.privacy.showModal', [ 'timeline' => $timeline->id ]) }}" data-modal data-modal-scroll data-modal-showclose="false" data-modal-clickclose="false" data-modal-class="modal-timeline-privacy">Privacy</a>
                    </li>
                </ul> 
            </li>  
        @endforeach
    </ul>
@else
    (no timelines yet)
@endif