@extends('layouts.timeline.master')

@push('scripts')
    @vite('resources/js/timeline/scripts-start.js')
@endpush

@section('content')

    <section class="timeline {{ $timeline->map ? 'timeline--map' : '' }}">
        <header class="timeline__header">
            @include('layouts.timeline.elements.header')
        </header>
        <div class="timeline__body">
            <div id="about" class="reveal">
                @include('layouts.timeline.elements.about')
            </div>
            <article class="scrollbar">
                @include('layouts.timeline.elements.events')
            </article>
            @if($timeline->map)
                <div id="map" data-satellite="{{ $timeline->map_satellite }}">
                    <div class="splitter">
                        <i class="fa-solid fa-grip-lines-vertical"></i>
                    </div>
                    <figure>
                        @include('layouts.timeline.elements.map')
                    </figure>            
                </div>
            @endif
            @if($tags->count())
                <div id="filters" class="reveal">
                    @include('layouts.timeline.elements.filters')
                </div>
            @endif
            @if($timeline->comment)
                <div id="comments" class="reveal">
                    @include('layouts.timeline.elements.comments')
                </div>
            @endif
        </div>
    </section>

    <div class="modal" id="modal-share">
        @include('layouts.modal.master', [ 'route' => 'layouts.timeline.snippets.modal.social', 'modal_title' => 'Share' ])
    </div>
    
    @guest
        <div class="modal" id="modal-signup">
            @include('layouts.modal.master', [ 'route' => 'layouts.global.snippets.modal.login-register', 'modal_title' => 'Log In or Register', 'show' => 'login', 'incentive' => 'You must log in to like or save...' ])
        </div>
    @endguest

@endsection