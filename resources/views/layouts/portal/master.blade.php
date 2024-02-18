<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/portal/styles.scss')
    @vite('resources/css/resource/form.scss')
    @vite('resources/css/plugin/tagify.css')
@endpush

@include('layouts.global.head')

<body>

    @include('layouts.global.topbar')

    <main>
        @yield('content')
    </main>

    <div class="backdrop"></div>

    @include('layouts.global.action')

    @push('scripts')
        @vite('resources/js/portal/scripts.js')
    @endpush

    @include('layouts.global.scripts')

</body>
</html>