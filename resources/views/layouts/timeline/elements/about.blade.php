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
                            {{ $summary }}.
                        </p>
                    @endif
                </div>
                <!-- this is all sources - should only be used sources -->
                @if($timeline->sources->count())
                    <div>
                        <strong>
                            Sources
                        </strong>
                        <ul>
                            @foreach($timeline->sources as $source) 
                                <li>
                                    <i class="{{ $source->fa_icon }}"></i><a href="{{ $source->url }}" target="_blank" rel="nofollow" title="">{{ $source->source }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <strong>
                        Editing
                    </strong>
                    <div>
                        <a data-modal data-modal-class="modal-suggest-edit" data-modal-size="modal-md" data-modal-showclose="true" href="{{ route('timeline.edit.showModal', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline">
                            <i class="fa-solid fa-pencil"></i>Suggest an edit
                        </a>
                        @if($timeline->collab)
                            <a href="#" class="btn btn-outline">
                                <i class="fa-solid fa-user-group"></i>Request to collaborate
                            </a>
                        @endif                  
                    </div>
                </div>
                <div>
                    <strong>
                        Report Timeline
                    </strong>
                    <div>
                        <a data-modal data-modal-class="modal-report" data-modal-size="modal-md" data-modal-showclose="true" href="{{ route('timeline.report.showModal', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline">
                            <i class="fa-solid fa-circle-exclamation"></i>Report
                        </a>
                    </div>
                </div>
            </div>    
        </div>
    </div>
</div>