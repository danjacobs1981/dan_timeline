<ul>

@foreach($timelines as $timeline)
        <li> <a href="{{ route('timeline.show', ['timeline' => $timeline->id,'slug' => $timeline->slug ]) }}">{{ $timeline->title }}</a></li>
        
 @endforeach

</ul>