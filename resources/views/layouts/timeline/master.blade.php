<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/timeline.scss')
@endpush

@include('layouts.global.head')

<body>

    <meta name="timeline" content="{{ $timeline->id }}">

    @include('layouts.global.topbar')
    
    <main>
        @yield('content')
    </main>

    <div class="backdrop"></div>

    @include('layouts.global.action')

    @push('scripts')
        @vite('resources/js/timeline/scripts.js')
    @endpush

    @include('layouts.global.scripts')

</body>
</html>