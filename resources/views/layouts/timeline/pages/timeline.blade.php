@extends('layouts.timeline.master')

@section('content')

    <section class="timeline {{ $temp_map ? 'timeline--map' : '' }}">
        <header class="timeline__header">
            @include('layouts.timeline.elements.header')
        </header>
        <div class="timeline__body">
            @if($temp_filters)
            <div id="filters" class="reveal">
                @include('layouts.timeline.elements.filters')
            </div>
            @endif
            <article>
                @include('layouts.timeline.elements.events')
            </article>
            @if($temp_map)
            <div id="map">
                <div class="splitter">
                    <i class="fa-solid fa-grip-lines-vertical"></i>
                </div>
                <figure>
                    @include('layouts.timeline.elements.map')
                </figure>            
            </div>
            @endif
            @if($temp_comments)
            <div id="comments" class="reveal">
                @include('layouts.timeline.elements.comments')
            </div>
            @endif
        </div>
    </section>

    <div class="modal" id="modal-share">
        @include('layouts.modal.master', ['route'=>'layouts.timeline.elements.social-share', 'modal_title'=>'Share'])
    </div>

@endsection