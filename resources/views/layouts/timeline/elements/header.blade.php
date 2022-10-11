<div>
    <div>
        <h1>{{ $timeline->title }}</h1>
        <em>
            by <strong><a href="#">username</a></strong>
        </em>
    </div>
    <div>
        <ul class="header__options">
            @auth
            <li class="header__options-edit colour-edit" data-popover="Edit" data-popover-position="bottom">
                <i class="fa-solid fa-pencil"></i><span>Edit</span>
            </li>
            @endauth
            @if($temp_filters)
            <li class="header__options-filters" data-reveal="filters" data-popover="Filter" data-popover-position="bottom">
                <i class="fa-solid fa-filter"></i><span>Filter</span>
            </li>
            @elseif(!$temp_filters && $temp_tags)
            <li class="header__options-tags dropdown-toggle dropdown-toggle-arrow" data-popover="Filter" data-popover-position="bottom">
                <i class="fa-solid fa-filter dropdown-close"></i>
                <span>Filter</span>
                <i class="fa-solid fa-chevron-down dropdown-close"></i>
                <!-- tag list -->
                <div class="dropdown dropdown-checkboxes" data-backdrop data-position="right">
                    <ul class="filter__wrapper">
                        <li class="filter__checkbox">
                            <input type="checkbox" id="f_Car" />
                            <label for="f_Car">
                                <span class="fa-stack">
                                    <i class="fa-regular fa-square fa-stack-1x"></i>
                                    <i class="fa-solid fa-square-check fa-stack-1x"></i>
                                </span>Car
                            </label>
                        </li>
                        <li class="filter__checkbox">
                            <input type="checkbox" id="f_Van" />
                            <label for="f_Van">
                                <span class="fa-stack">
                                    <i class="fa-regular fa-square fa-stack-1x"></i>
                                    <i class="fa-solid fa-square-check fa-stack-1x"></i>
                                </span>Van
                            </label>
                        </li>
                        <li class="filter__checkbox">
                            <input type="checkbox" id="f_4x4" />
                            <label for="f_4x4">
                                <span class="fa-stack">
                                    <i class="fa-regular fa-square fa-stack-1x"></i>
                                    <i class="fa-solid fa-square-check fa-stack-1x"></i>
                                </span>4x4
                            </label>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            <li class="header__options-like colour-like" data-popover="Like" data-popover-position="bottom">
                <i class="fa-regular fa-heart"></i><i class="fa-solid fa-heart"></i><span>Like</span>
            </li>
            <!--<li class="header__options-like colour-liked">
                <i class="fa-regular fa-heart"></i><i class="fa-solid fa-heart"></i><span>21</span>
            </li>-->
            <li class="header__options-save colour-save" data-popover="Save" data-popover-position="bottom">
                <i class="fa-regular fa-bookmark"></i><i class="fa-solid fa-bookmark"></i><span>Save</span>
            </li>
            <!--<li class="header__options-save colour-saved">
                <i class="fa-regular fa-bookmark"></i><i class="fa-solid fa-bookmark"></i><span>Saved</span>
            </li>-->
            <li class="header__options-comments" data-reveal="comments" data-popover="Comments" data-popover-position="bottom">
                <i class="fa-solid fa-comment"></i><span>8 comments</span>
            </li>
            <li class="header__options-share" data-popover="Share" data-popover-position="bottom">
                <i class="fa-solid fa-share-nodes"></i><span>Share</span>
                @include('layouts.timeline.elements.social',['more'=>true])
            </li>
            <li class="header__options-info dropdown-toggle" data-popover="More" data-popover-position="bottom">
                <i class="fa-solid fa-ellipsis dropdown-close"></i><span>More</span>
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