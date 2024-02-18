<!DOCTYPE html>
<html>
@push('stylesheets')
    @vite('resources/css/timeline/styles.scss')
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

    @include('layouts.global.scripts')

</body>
</html>