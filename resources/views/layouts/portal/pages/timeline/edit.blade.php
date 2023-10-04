@extends('layouts.portal.master')

@push('stylesheets')
    @vite('resources/css/portal/edit.scss')
@endpush
@push('scripts')
    @vite('resources/js/portal/timeline/edit.js')
@endpush

@section('content')

    <!--<meta name="timeline" content="{{ $timeline->id }}">-->

    @if(session('helper'))
        <p>
            <strong>
                timeline created - now add some events and hit publish when ready
            </strong>
        </p>
    @endif

    <div class="edit">

        <section class="edit__section">

            <header>

                <h1>{{ $timeline->title }}</h1>

                <!-- (gives a warning if something isn't saved) -->
                <a href="{{ route('timelines.index') }}" class="btn">
                    Exit Editing Timeline
                </a>
    
                <p>
                    Timeline link: <a target="_blank" href="{{ route('timeline.show', ['timeline' => $timeline->id]) }}">{{ route('timeline.show', ['timeline' => $timeline->id]) }}</a>
                </p>
    
                @if(old('privacy', $timeline->privacy) === 0)
                    <span class="privacy-draft">
                        <i class="fa-solid fa-circle-exclamation"></i> Timeline is currently a draft
                    </span>
                @endif

            </header>

            <ul>
                <li>
                    <a href="#general" data-text="General">General</a>
                </li>
                <li>
                    <a href="#events" data-text="Events">Events</a>
                </li>
                <li>
                    <a href="#visibility" data-text="Visibility">Visibility</a>
                </li>
                <li>
                    <a href="#collaborators" data-text="Collaborators">Collaborators</a>
                </li>
                <li>
                    <a href="#options" data-text="Further Options">Further Options</a>
                </li>
            </ul>

            <section>

                <section id="general" class="edit__tab" style="display:none;">

                    <h2>General Settings</h2>
                    <p>Intro to this section</p>
                    <a href="#visibility" class="tab">vis</a>

                    @include('layouts.portal.snippets.edit-settings')

                </section>

                <section id="visibility" class="edit__tab" style="display:none;">

                    <h2>Visibility</h2>
                    <p>Intro to this section</p>

                    @include('layouts.portal.snippets.edit-privacy')

                </section>

                <section id="collaborators" class="edit__tab" style="display:none;">

                    <h2>Collaborators</h2>
                    <p>Intro to this section</p>

                </section>

                <section id="options" class="edit__tab" style="display:none;">

                    <h2>Further Options</h2>
                    <p>Intro to this section</p>

                    <form action="{{ route('timelines.destroy', $timeline->id) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn">Delete timeline</button>
                    </form>

                </section>

            </section>

        </section>

        <section id="events" class="edit__tab edit__events" style="display:none;">

            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            events<br/>
            end<br/>

        </section>

    </div>
    
@endsection