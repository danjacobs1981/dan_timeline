@inject('carbon', 'Carbon\Carbon')
@push('stylesheets')
    @vite('resources/css/about.scss')
@endpush
@php
    $date = $timeline->events->sortBy('order_overall')->value('date_unix');
    $start_date = $carbon::createFromTimestamp($timeline->events->sortBy('order_overall')->value('date_unix'))->format('jS F Y');
    $end_date = $carbon::createFromTimestamp($timeline->events->sortByDesc('order_overall')->value('date_unix'))->format('jS F Y');
@endphp
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
                    by <strong><a href="{{ route('profile.show', ['username' => $timeline->user->username ]) }}">{{ $timeline->user->username }}</a></strong> <!--<span>&plus; <span>X collaborators</span></span>-->
                </em>
                <span>
                    (last updated: {{ $timeline->updated_at->format('jS F Y g:ia') }})
                </span>
                <div>
                    @if($timeline->description)
                        {!! preg_replace("#<p>(\s|&nbsp;|</?\s?br\s?/?>)*</?p>#", '', '<p>'.implode('</p><p>', array_filter(explode("\n", $timeline->description))).'</p>') !!}
                    @else
                        <p>
                            @if($date)
                                @if($start_date === $end_date)
                                    The timeline of events that happened on the {{ $start_date }}.
                                @else
                                    The timeline of events that spanned between the {{ $start_date }} and the {{ $end_date }}.
                                @endif
                            @else
                                A timeline of events.
                            @endif
                        </p>
                    @endif
                </div>
                <!-- this is all sources - should only be used sources -->
                @if($timeline->sources->count())
                    <div>
                        <strong>Timeline Sources</strong>
                        <ul>
                            @foreach($timeline->sources as $source) 
                                <li>
                                    <i class="{{ $source->fa_icon }}"></i><a href="{{ $source->url }}" target="_blank" rel="nofollow" title="">{{ $source->source }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>    
        </div>
    </div>
</div>