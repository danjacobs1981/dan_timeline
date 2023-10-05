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

            <div>

                <header>
                    <div>
                        <div>
                            <h1>
                                {{ $timeline->title }}
                            </h1>
                        </div>
                        <div>
                            <ul class="header__options">
                                <a href="{{ route('timeline.show', ['timeline' => $timeline->id]) }}" target="_blank">
                                    <i class="fa-regular fa-window-restore"></i> <span>View Timeline</span>
                                </a>
                                <a href="{{ route('timelines.index') }}" class="colour-edit">
                                    <i class="fa-solid fa-chevron-left"></i> <span>Exit Editing</span><!-- (gives a warning if something isn't saved) -->
                                </a>
                            </ul>
                        </div>
                    </div>
                    
                    <ul>
                        <li>
                            <a href="#general-tab">Settings</a>
                        </li>
                        <li>
                            <a href="#events-tab">Events</a>
                        </li>
                        <li>
                            <a href="#visibility-tab">Visibility</a>
                        </li>
                        <li>
                            <a href="#tags-tab">Tags &amp; Filters</a>
                        </li>
                        <li>
                            <a href="#comments-tab">Comments</a>
                        </li>
                        <li>
                            <a href="#collaborators-tab">Collaborators</a>
                        </li>
                        <li>
                            <a href="#more-tab">More</a>
                        </li>
                    </ul>

                </header>

                <section class="scrollbar">

                    <section id="general-tab" class="edit__tab" style="display:none;">

                        @if(old('privacy', $timeline->privacy) === 0)
                            <a href="#visibility" class="tab privacy-draft">
                                <i class="fa-solid fa-circle-exclamation"></i> Timeline is currently a draft
                            </a>
                        @endif

                        <p>Intro to this section</p>
                        <a href="#visibility" class="tab">vis</a>

                        @include('layouts.portal.snippets.edit-settings')

                    </section>

                    <section id="visibility-tab" class="edit__tab" style="display:none;">

                        <p>Intro to this section</p>

                        @include('layouts.portal.snippets.edit-privacy')

                    </section>

                    <section id="tags-tab" class="edit__tab" style="display:none;">

                        <p>Intro to this section</p>

                    </section>

                    <section id="comments-tab" class="edit__tab" style="display:none;">

                        <p>Intro to this section</p>

                    </section>

                    <section id="collaborators-tab" class="edit__tab" style="display:none;">

                        <p>Intro to this section</p>

                    </section>

                    <section id="more-tab" class="edit__tab" style="display:none;">

                        <p>Intro to this section</p>

                        <form action="{{ route('timelines.destroy', $timeline->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn">Delete timeline</button>
                        </form>

                    </section>

                </section>

            </div>

            <section id="events" class="edit__tab edit__events scrollbar" style="display:none;">

                add event
    
            </section>

        </section>

    </div>
    
@endsection