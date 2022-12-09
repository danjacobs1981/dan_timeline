<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/timeline.scss')
@endpush
@include('layouts.global.head')

<body>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="timeline" content="{{ $timeline->id }}">

    @include('layouts.global.topbar')

    @yield('content')

    <div class="backdrop"></div>

    @push('scripts')
        @vite('resources/js/timeline.js')
    @endpush
    @include('layouts.global.scripts')

</body>
</html>