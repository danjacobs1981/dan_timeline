<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/web.scss')
@endpush
@include('layouts.global.head')

<body>

    @include('layouts.global.topbar')

    @yield('content')

    @include('layouts.web.elements.footer')

    <div class="backdrop" data-status="hide"></div>

    @push('scripts')
        @vite('resources/js/web.js')
    @endpush
    @include('layouts.global.scripts')

</body>
</html>