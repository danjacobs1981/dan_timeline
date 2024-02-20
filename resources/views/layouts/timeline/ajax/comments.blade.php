@inject('carbon', 'Carbon\Carbon')
<textarea>

</textarea>

@forelse($comments->where('parent_id', null)->sortBy('created_at') as $comment)  
    @include('layouts.timeline.ajax.comments-comment', [ 'reply' => false ])
    @if($comments->whereNotNull('parent_id')->where('parent_id', $comment->id)->count())
        <div class="replies">
            @foreach($comments->whereNotNull('parent_id')->where('parent_id', $comment->id)->sortBy('created_at') as $comment)  
                @include('layouts.timeline.ajax.comments-comment', [ 'reply' => true ])
            @endforeach
        </div>
    @endif
@empty
    <p>
        Start the conversation!
    </p>
@endforelse
