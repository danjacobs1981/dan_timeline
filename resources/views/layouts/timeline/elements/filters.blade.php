@push('stylesheets')
    @vite('resources/css/filters.scss')
@endpush
@push('scripts')
    @vite('resources/js/filters.js')
@endpush
<div class="reveal__wrapper">
    <div class="reveal__header">
        <h3>Filters</h3>
        <span class="fa-stack reveal__close">
            <i class="fa-solid fa-circle fa-stack-2x"></i>
            <i class="fa-solid fa-xmark fa-stack-1x"></i>
        </span>
    </div>
    <div class="reveal__body filter__wrapper">
        <ul class="filter__group">
            <li>
                <h3>Vehicles</h3>
                <ul class="filter__checkboxes">
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
                        <input type="checkbox" id="f_Lorry" />
                        <label for="f_Lorry">
                            <span class="fa-stack">
                                <i class="fa-regular fa-square fa-stack-1x"></i>
                                <i class="fa-solid fa-square-check fa-stack-1x"></i>
                            </span>Lorry
                        </label>
                    </li>      
                </ul>
            </li>
            <li>
                <h3>Sources</h3>
                <ul class="filter__checkboxes">
                <li class="filter__checkbox">
                        <input type="checkbox" id="f_Verified" />
                        <label for="f_Verified">
                            <span class="fa-stack">
                                <i class="fa-regular fa-square fa-stack-1x"></i>
                                <i class="fa-solid fa-square-check fa-stack-1x"></i>
                            </span>Verified
                        </label>
                    </li>
                    <li class="filter__checkbox">
                        <input type="checkbox" id="f_Unverified" />
                        <label for="f_Unverified">
                            <span class="fa-stack">
                                <i class="fa-regular fa-square fa-stack-1x"></i>
                                <i class="fa-solid fa-square-check fa-stack-1x"></i>
                            </span>Unverified
                        </label>
                    </li>
                </ul>
            </li>
            <li>
                <h3>People</h3>
                <ul class="filter__checkboxes">
                    <li class="filter__checkbox">
                        <input type="checkbox" id="f_p4" />
                        <label for="f_p4">
                            <span class="fa-stack">
                                <i class="fa-regular fa-square fa-stack-1x"></i>
                                <i class="fa-solid fa-square-check fa-stack-1x"></i>
                            </span><img src="{{ Vite::asset('resources/images/test/person2.jpg') }}" alt="" />Bill Spoony
                        </label>
                    </li>
                    <li class="filter__checkbox">
                        <input type="checkbox" id="f_p5" />
                        <label for="f_p5">
                            <span class="fa-stack">
                                <i class="fa-regular fa-square fa-stack-1x"></i>
                                <i class="fa-solid fa-square-check fa-stack-1x"></i>
                            </span><img src="{{ Vite::asset('resources/images/test/person2.jpg') }}" alt="" />Fred Marshall
                        </label>
                    </li>
                    <li class="filter__checkbox">
                        <input type="checkbox" id="f_p6" />
                        <label for="f_p6">
                            <span class="fa-stack">
                                <i class="fa-regular fa-square fa-stack-1x"></i>
                                <i class="fa-solid fa-square-check fa-stack-1x"></i>
                            </span><span>DJ</span>Don Jude
                        </label>
                    </li>
                </ul>     
            </li>
        </ul>
    </div>
    <div class="reveal__footer">
        <span class="filter__clear">
            Clear all
        </span>
        <span class="btn filter__show">
            Show {{ $timeline->events->count() }} results
        </span>
    </div>
</div>