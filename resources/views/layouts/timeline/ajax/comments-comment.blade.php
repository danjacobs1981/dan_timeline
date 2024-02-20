
<div class="comment" data-id="{{ $comment->id }}">
    <header>
        <div>
            <div>
                <span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-regular fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-user fa-stack-1x"></i>
				</span>
            </div>
            <div>
                <strong>
                    <a href="{{ route('profile.show', ['username' => $comment->author->username ]) }}" target="_blank">
                        {{ $comment->author->username }}
                    </a>
                </strong>
                <em>
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
                            <i class="fa-solid fa-reply"></i>Reply
                        </a>
                    </li>
                    <span></span>
                    <li>
                        <a href="#">
                            <i class="fa-solid fa-circle-exclamation"></i>Report
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
            <div>
                like
            </div>
            <span>
                #
            </span>
        </div>
        <a href="#">
            Reply
        </a>
    </footer> 
</div>