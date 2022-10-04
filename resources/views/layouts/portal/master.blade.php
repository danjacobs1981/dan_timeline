<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/portal.scss')
@endpush
@include('layouts.global.head')

<body>

    @include('layouts.global.topbar')

    @yield('content') ??? prob will have diff structure

    <div class="backdrop" data-status="hide"></div>

    @push('scripts')
        @vite('resources/js/portal.js')
    @endpush
    @include('layouts.global.scripts')

</body>
</html>