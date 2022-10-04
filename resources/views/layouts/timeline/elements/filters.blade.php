@push('stylesheets')
    @vite('resources/css/filters.scss')
@endpush
@push('scripts')
    @vite('resources/js/filters.js')
@endpush
<div class="filters">        
    <span class="fa-stack">
        <i class="fa-solid fa-circle fa-stack-2x"></i>
        <i class="fa-solid fa-xmark fa-stack-1x"></i>
    </span>
    <h3>Filters</h3>
    <ul>
        <li class="dropdown-toggle filter">
            Vehicles <span></span><i class="fa-solid fa-chevron-down dropdown-close"></i>
            <div class="dropdown">
                <ul>
                    <li>
                        <span class="checkbox">
                            <i class="fa-regular fa-square"></i>Car
                        </span>
                    </li>
                    <li>
                        <span class="checkbox">
                            <i class="fa-regular fa-square"></i>Van
                        </span>
                    </li>
                    <li>
                        <span class="checkbox">
                            <i class="fa-regular fa-square"></i>4x4
                        </span>
                    </li>
                    <span></span>
                    <li>
                        <a href="#"><i class="fa-solid fa-check"></i>Done</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="dropdown-toggle filter">
            Places <span>3</span><i class="fa-solid fa-chevron-down dropdown-close"></i>
            <div class="dropdown">
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
        <li class="filter">
            People
        </li>
    </ul>
</div>