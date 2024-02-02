<div>
    <div>
        <h1>
            {{ $timeline->title }}
        </h1>
        <em>
            by <strong><a href="{{ route('profile.show', ['username' => $timeline->user->username ]) }}">{{ $timeline->user->username }}</a></strong> <span>&plus; <span>2 collaborators</span></span>
        </em>
    </div>
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
                <li class="header__options-filters" data-reveal="filters" data-popover="Filter" data-popover-position="top">
                    <i class="fa-solid fa-sliders"></i><span>Filter</span>
                </li>
            @endif
            @if($timeline->comments)
                <li class="header__options-comments" data-reveal="comments" data-popover="Comments" data-popover-position="top">
                    <i class="fa-regular fa-comment"></i><span>8 Remarks</span>
                </li>
            @endif
            @if(auth()->check() && $timeline->likedByUser())
                <li class="header__options-like color-liked">
                    <i class="fa-solid fa-circle-notch fa-spin"></i><i class="fa-regular fa-heart"></i><i class="fa-solid fa-heart fa-bounce"></i><span>{{ $timeline->likesCount() }} Stars</span>
                </li>
            @else
                <li class="header__options-like color-like">
                    <i class="fa-solid fa-circle-notch fa-spin"></i><i class="fa-regular fa-heart"></i><i class="fa-solid fa-heart"></i><span>{{ $timeline->likesCount() }} Stars</span>
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

        <!--<ul class="header__options-secondary">
            @if(auth()->check() && $timeline->user_id == auth()->id())
                <a class="header__options-edit link" href="{{ route('timelines.edit', ['timeline' => $timeline->id ]) }}">
                    <i class="fa-solid fa-pencil"></i><span>Edit</span>
                </a>
            @endif
            <li class="header__options-share" data-popover="Share" data-popover-position="top">
                <i class="fa-solid fa-share-nodes"></i><span>Share</span>
                @include('layouts.timeline.snippets.social',['more'=>true])
            </li>
            <li class="header__options-info dropdown-toggle" data-popover="More" data-popover-position="top">
                <i class="fa-solid fa-ellipsis dropdown-close"></i><span>More</span>
                <div class="dropdown" data-backdrop data-position-x="right">
                    <ul>
                        <li>
                            <a href="{{ route('profile.show', ['username' => $timeline->user->username ]) }}">
                                <i class="fa-regular fa-user"></i>See more by <strong>{{ $timeline->user->username }}</strong>
                            </a>
                        </li>
                        <li>
                            <a href="#">
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
                            <a href="#">
                                <i class="fa-solid fa-circle-exclamation"></i>Report
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>-->
    </div>
</div>