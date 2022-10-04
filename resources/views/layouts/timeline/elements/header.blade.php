<div>
    <div>
        <h1>{{ $timeline->title }}</h1>
        <em>
            by <strong><a href="#">username</a></strong>
        </em>
        <span class="fa-stack">
            <i class="fa-solid fa-circle fa-stack-2x"></i>
            <i class="fa-solid fa-xmark fa-stack-1x"></i>
        </span>
    </div>
    <div>
        <ul class="header__options">
            @auth
            <li class="header__options-edit colour-edit">
                <i class="fa-solid fa-pencil"></i>Edit
            </li>
            @endauth
            <li class="header__options-filters">
                <i class="fa-solid fa-filter"></i>Filter
            </li>
            <li class="header__options-like colour-like">
                <i class="fa-regular fa-heart"></i><i class="fa-solid fa-heart"></i>Like
            </li>
            <!--<li class="header__options-like colour-liked">
                <i class="fa-regular fa-heart"></i><i class="fa-solid fa-heart"></i>21
            </li>-->
            <li class="header__options-save colour-save">
                <i class="fa-regular fa-bookmark"></i><i class="fa-solid fa-bookmark"></i>Save
            </li>
            <!--<li class="header__options-save colour-saved">
                <i class="fa-regular fa-bookmark"></i><i class="fa-solid fa-bookmark"></i>Saved
            </li>-->
            <li class="header__options-comments">
                <i class="fa-solid fa-comment"></i>8 comments
            </li>
            <li class="header__options-share">
                <i class="fa-solid fa-share-nodes"></i>Share
                @include('layouts.timeline.elements.social',['more'=>true])
            </li>
            <li class="header__options-info dropdown-toggle">
                <i class="fa-solid fa-ellipsis dropdown-close"></i>More
                <div class="dropdown" data-backdrop data-position="right">
                    <ul>
                        <li>
                            <a href="#"><i class="fa-regular fa-user"></i>See more by <strong>username</strong></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa-solid fa-pencil"></i>Suggest an edit</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa-solid fa-user-group"></i>Request to collaborate</a>
                        </li>
                        <span></span>
                        <li>
                            <a href="#"><i class="fa-solid fa-circle-exclamation"></i>Report</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
@include('layouts.timeline.elements.filters')
