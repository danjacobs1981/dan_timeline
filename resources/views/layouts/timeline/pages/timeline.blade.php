@extends('layouts.timeline.master')

@section('content')

    <main>
        <section class="timeline {{ $temp_map ? 'timeline--map' : '' }} {{ $temp_filters ? 'timeline--filters' : '' }}">
            <header class="timeline__header">
                @include('layouts.timeline.elements.header')
            </header>
            <div class="timeline__body">
                @if($temp_filters)
                <div id="filters">
                    @include('layouts.timeline.elements.filters')
                </div>
                @endif
                <article>
                    @include('layouts.timeline.elements.events')
                </article>
                @if($temp_map)
                <div class="splitter">
                    <i class="fa-solid fa-grip-lines-vertical"></i>
                </div>
                <figure>
                    @include('layouts.timeline.elements.map')
                </figure>            
                @endif
                @if($temp_comments)
                <div id="comments">
                    @include('layouts.timeline.elements.comments')
                </div>
                @endif
            </div>
        </section>
    </main>

    <div class="modal" id="modal-share">
        @include('layouts.timeline.modal.share', ['modal_title'=>'Share'])
    </div>

@endsection