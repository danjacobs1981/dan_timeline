<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/portal.scss')
@endpush

@include('layouts.global.head')

<body>

    @include('layouts.global.topbar')

    @yield('content')

    <div class="backdrop" data-status="hide"></div>

    @push('scripts')
        @vite('resources/js/portal.js')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ App::environment() == 'local' ? config('constants.map.key.local') : config('constants.map.key.production') }}&callback=initMap&v=weekly" defer></script>
    @endpush
    @include('layouts.global.scripts')

</body>
</html>