@push('stylesheets')
    @vite('resources/css/about.scss')
@endpush
<div class="reveal__wrapper">
    <div class="reveal__header">
        <strong>About</strong>
        <span class="fa-stack reveal__close">
            <i class="fa-solid fa-circle fa-stack-2x"></i>
            <i class="fa-solid fa-xmark fa-stack-1x"></i>
        </span>
    </div>
    <div class="reveal__body">
        <div>
            @if($timeline->image)
                <img src="{{ asset('storage/images/timeline/'.$timeline->id.'/'.$timeline->image) }}" alt="{{ html_entity_decode($timeline->title) }}">
            @endif
            <div>
                <h2>
                    {{ $timeline->map ? 'Visual Timeline:' : 'Timeline:' }} {{ $timeline->title }}
                </h2>
                <em>
                    by <strong><a href="{{ route('profile.show', ['username' => $timeline->user->username ]) }}">{{ $timeline->user->username }}</a></strong> <span>&plus; <span>2 collaborators</span></span>
                </em>
                <span>
                    Last updated: XXX
                </span>
                <div>
                    @if($timeline->description)
                        {!! preg_replace("#<p>(\s|&nbsp;|</?\s?br\s?/?>)*</?p>#", '', '<p>'.implode('</p><p>', array_filter(explode("\n", $timeline->description))).'</p>') !!}
                    @else
                        <p>
                            Generated summary...
                        </p>
                    @endif
                </div>
            </div>    
        </div>
    </div>
</div>