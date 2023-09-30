@extends('layouts.portal.master')

@push('scripts')
    @vite('resources/js/portal/timeline/edit.js')
    @vite('resources/js/portal/timeline/ajax/settings.js')
    @vite('resources/js/portal/timeline/ajax/privacy.js')
@endpush

@section('content')

    <meta name="timeline" content="{{ $timeline->id }}">

    @if(session('helper'))
        <p>
            <strong>
                timeline created - now add some events and hit publish when ready
            </strong>
        </p>
    @endif

    <h1>edit timeline</h1>

    <a href="{{ route('timelines.index') }}" class="btn">Exit Editing Timeline</a> (gives a warning if something isn't saved)

    <div style="border:1px solid black;padding:20px;margin-top:20px;">
        Timeline link: <a target="_blank" href="{{ route('timeline.show', ['timeline' => $timeline->id]) }}">{{ route('timeline.show', ['timeline' => $timeline->id]) }}</a>
    </div>

    <div style="border:1px solid black;padding:20px;margin-top:20px;">
        
        <h3>settings</h3>

        <div id="timelineSettings">
            <div>
                <label for="title">Title</label>
                <input name="title" id="title" data-value="{{ old('title', $timeline->title) }}" value="{{ old('title', $timeline->title) }}">
            </div>

            <div>
                <label for="comments">Show Comments?</label>
                <input type="hidden" name="comments" value="0">
                <input type="checkbox" name="comments" id="comments" value="1" {{ old('comments', $timeline->comments) ? 'checked' : '' }}>
            </div>

            <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update Settings</button>
        </div>


    </div>


    <div style="border:1px solid black;padding:20px;margin-top:20px;">

        <h3>privacy</h3>

        @include('layouts.portal.snippets.edit-privacy')

    </div>

    <div id="timelineCollaborators" style="border:1px solid black;padding:20px;margin-top:20px;">

        <h3>collaborators</h3>

    </div>

    <form action="{{ route('timelines.destroy', $timeline->id) }}" method="POST">
        @method('DELETE')
        @csrf

        <button type="submit" class="btn">Delete timeline</button>

    </form>
    
@endsection