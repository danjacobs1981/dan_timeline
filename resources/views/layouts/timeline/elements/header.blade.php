<div>
    <h1>
        {{ $timeline->title }}
    </h1>
    <div>
        <ul class="header__options-primary">
            <li class="header__options-info" data-reveal="about">
                <i class="fa-solid fa-circle-info"></i><span>About</span>
            </li>
            @if($timeline->map)
                <li class="header__options-map">
                    <i class="fa-regular fa-map"></i><span>Map</span>
                </li>
            @endif
            @if($tags->count())
                <li class="header__options-filters" data-reveal="filters">
                    <i class="fa-solid fa-sliders"></i><em>0</em><span>Filter</span>
                </li>
            @endif
            @if($timeline->comments)
                <li class="header__options-comments" data-reveal="comments">
                    <i class="fa-regular fa-comment"></i><span>8</span>
                </li>
            @endif
            @if(auth()->check() && $timeline->likedByUser())
                <li class="header__options-like color-liked">
                    <i class="fa-solid fa-circle-notch fa-spin"></i><i class="fa-regular fa-heart"></i><i class="fa-solid fa-heart fa-bounce"></i><span>{{ $timeline->likesCount() }}</span>
                </li>
            @else
                <li class="header__options-like color-like">
                    <i class="fa-solid fa-circle-notch fa-spin"></i><i class="fa-regular fa-heart"></i><i class="fa-solid fa-heart"></i><span>{{ $timeline->likesCount() }}</span>
                </li>
            @endif
            @if(auth()->check() && $timeline->savedByUser())
                <li class="header__options-save color-saved">
                    <i class="fa-solid fa-circle-notch fa-spin"></i><i class="fa-regular fa-bookmark"></i><i class="fa-solid fa-bookmark fa-bounce"></i><span>Saved</span>
                </li>
            @else
                <li class="header__options-save color-save">
                    <i class="fa-solid fa-circle-notch fa-spin"></i><i class="fa-regular fa-bookmark"></i><i class="fa-solid fa-bookmark"></i><span>Save</span>
                </li>
            @endif
        </ul>

        <ul class="header__options-secondary">
            @if(auth()->check() && $timeline->user_id == auth()->id())
                <li class="header__options-edit">
                    <a href="{{ route('timelines.edit', ['timeline' => $timeline->id ]) }}">
                        <i class="fa-solid fa-pencil"></i><span>Edit</span>
                    </a>
                </li>
            @endif
            <li class="header__options-share">
                <i class="fa-solid fa-share-nodes"></i><span>Share</span>
            </li>
            <li class="header__options-info dropdown-toggle">
                <i class="fa-solid fa-ellipsis dropdown-close"></i><span>More</span>
                <div class="dropdown" data-backdrop data-position-x="left">
                    <ul>
                        <li>
                            <a href="{{ route('profile.show', ['username' => $timeline->user->username ]) }}">
                                <i class="fa-regular fa-user"></i>See more by <strong>{{ $timeline->user->username }}</strong>
                            </a>
                        </li>
                        <li>
                            <a data-modal data-modal-scroll data-modal-class="modal-suggestion" data-modal-size="modal-md" data-modal-showclose="true" data-modal-clickclose="false" href="{{ route('timeline.suggestion.showModal', [ 'timeline' => $timeline->id ]) }}">
                                <i class="fa-solid fa-pencil"></i>Suggest an edit
                            </a>
                        </li>
                        @if($timeline->collab)
                            <li>
                                <a href="#">
                                    <i class="fa-solid fa-user-group"></i>Request to collaborate
                                </a>
                            </li>
                        @endif
                        <span></span>
                        <li>
                            <a data-modal data-modal-scroll data-modal-class="modal-report" data-modal-size="modal-md" data-modal-showclose="true" data-modal-clickclose="false" href="{{ route('timeline.report.showModal', [ 'timeline' => $timeline->id ]) }}">
                                <i class="fa-solid fa-circle-exclamation"></i>Report
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>