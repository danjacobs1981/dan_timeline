<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/timeline.scss')
@endpush
@include('layouts.global.head')

<body>

    @include('layouts.global.topbar')

    @yield('content')

    <div class="backdrop"></div>

    @push('scripts')
        @vite('resources/js/timeline.js')
    @endpush
    @include('layouts.global.scripts')

</body>
</html>