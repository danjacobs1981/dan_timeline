@extends('layouts.portal.master')

@push('stylesheets')
    @vite('resources/css/portal/timeline/edit.scss')
@endpush
@push('scripts')
    @vite('resources/js/portal/timeline/edit.js')
@endpush

@section('content')

    <meta name="timeline" content="{{ $timeline->id }}">

    <div id="timelineEdit" class="edit">

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
                                <a href="{{ route('timelines.index') }}">
                                    <i class="fa-solid fa-chevron-left"></i> <span>Exit Editing</span><!-- (gives a warning if something isn't saved) -->
                                </a>
                            </ul>
                        </div>
                    </div>
                    
                    <ul class="tabs tabs--timeline">
                        <li>
                            <a href="#settings-tab">Settings</a>
                        </li>
                        <li>
                            <a href="#events-tab">Events</a>
                        </li>
                        <li>
                            <a href="#about-tab">About</a>
                        </li>
                        <li>
                            <a href="#tags-tab">Tagging</a>
                        </li>
                        <li>
                            <a href="#sources-tab">Sources</a>
                        </li>
                        <li>
                            <a href="#comments-tab">Comments</a>
                        </li>
                        <li>
                            <a href="#collaborators-tab">Collaborators</a>
                        </li>
                        <li>
                            <a href="#delete-tab">Delete</a>
                        </li>
                    </ul>

                </header>

                <section class="scrollbar">

                    <section id="settings-tab" class="edit__tab" style="display:none;">

                        @if(session('helper'))
                            <p>
                                <strong>
                                    timeline created - now add some events and hit publish when ready
                                </strong>
                            </p>
                        @endif

                        @include('layouts.portal.snippets.timeline-settings')

                    </section>

                    <section id="about-tab" class="edit__tab" style="display:none;">

                        @include('layouts.portal.snippets.timeline-about')

                    </section>

                    <section id="tags-tab" class="edit__tab" style="display:none;">

                        @include('layouts.portal.snippets.timeline-event-tags', [ 'placement' => 'timeline', 'tags' => null ])

                    </section>

                    <section id="sources-tab" class="edit__tab" style="display:none;">

                        @include('layouts.portal.snippets.timeline-event-sources', [ 'placement' => 'timeline', 'sources' => null ])

                    </section>

                    <section id="comments-tab" class="edit__tab" style="display:none;">

                        @include('layouts.portal.snippets.timeline-comments')

                    </section>

                    <section id="collaborators-tab" class="edit__tab" style="display:none;">

                        @include('layouts.portal.snippets.timeline-collaborators')

                    </section>

                    <section id="delete-tab" class="edit__tab" style="display:none;">

                        @include('layouts.portal.snippets.timeline-delete')

                    </section>

                </section>

            </div>

            <section id="events-tab" class="edit__tab edit__events" style="display:none;">

                @include('layouts.portal.snippets.timeline-events')

            </section>

        </section>

    </div>
    
@endsection