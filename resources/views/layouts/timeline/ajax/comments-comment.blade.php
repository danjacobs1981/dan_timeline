
<div class="comment" data-id="{{ $comment->id }}">
    <header>
        <div>
            <div>
                <span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-user fa-stack-1x"></i>
				</span>
            </div>
            <div>
                <a href="{{ route('profile.show', ['username' => $comment->author->username ]) }}" target="_blank">
                    {{ $comment->author->username }} {!! $comment->timeline->user_id == $comment->user_id ? '<span>Author</span>' : '' !!} 
                </a>
                <em data-popover="{{ $comment->created_at->format('jS F Y g:ia') }}" data-popover-position="bottom">
                    {{ $carbon::parse($comment->created_at)->diffForHumans() }}
                </em>
            </div>
        </div>
        <span class="dropdown-toggle">
            <i class="fa-solid fa-ellipsis dropdown-close"></i>
            <div class="dropdown" data-backdrop data-position-x="right" data-position-y="bottom">
                <ul>
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-reply"></i>Reply to comment
                        </a>
                    </li>
                    <span></span>
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-circle-exclamation"></i>Report comment
                        </a>
                    </li>
                </ul>
            </div>
        </span>
    </header>
    <div>
        {!! preg_replace("#<p>(\s|&nbsp;|</?\s?br\s?/?>)*</?p>#", '', '<p>'.implode('</p><p>', array_filter(explode("\n", $comment->comment))).'</p>') !!}
    </div>
    <footer>
        <div>
            @if(auth()->check() && $comment->likes->where('user_id', auth()->id())->first())
                <i class="fa-solid fa-thumbs-up"></i>
            @else
                <i class="fa-regular fa-thumbs-up"></i>
            @endif
            <span>
                {{ $comment->likesCount() }}
            </span>
        </div>
        <a href="#">
            Reply
        </a>
    </footer> 
</div>