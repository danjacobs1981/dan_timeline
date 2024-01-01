@if(count(auth()->user()->timelinesLiked))
    <ul>
        @foreach(auth()->user()->timelinesLiked as $timeline)
            <li>
                {{ $timeline->title }} 
                <ul>
                    <li>
                        <a href="{{ route('timeline.show', ['timeline' => $timeline->id,'slug' => $timeline->slug ]) }}">View</a>
                    </li>
                </ul> 
            </li>  
        @endforeach
    </ul>
@else
    (you have not 'liked' any timelines yet)
@endif