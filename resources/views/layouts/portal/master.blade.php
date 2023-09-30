<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/portal.scss')
@endpush

@include('layouts.global.head')

<body>

    @include('layouts.global.topbar')

    <main>
        @yield('content')
    </main>

    @include('layouts.web.elements.footer')

    <div class="backdrop"></div>

    @include('layouts.global.action')

    @push('scripts')
        @vite('resources/js/portal/scripts.js')
    @endpush

    @include('layouts.global.scripts')

</body>
</html>