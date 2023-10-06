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
                                <a href="{{ route('timelines.index') }}" class="link-outline">
                                    <i class="fa-solid fa-chevron-left"></i> <span>Exit Editing</span><!-- (gives a warning if something isn't saved) -->
                                </a>
                            </ul>
                        </div>
                    </div>
                    
                    <ul>
                        <li>
                            <a href="#general-tab">General</a>
                        </li>
                        <li>
                            <a href="#events-tab">Events</a>
                        </li>
                        <li>
                            <a href="#about-tab">About</a>
                        </li>
                        <li>
                            <a href="#tags-tab">Tags / Filters</a>
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

                        <p>Intro to this section</p>

                        @include('layouts.portal.snippets.edit-settings')
                        
                        <div class="visibility">
                            <em>
                                Timeline Visibility
                            </em>
                            <strong>
                                <i class="fa-regular fa-eye public"></i>Public
                            </strong>
                            <!--
                                <i class="fa-regular fa-eye-slash"></i>
                            -->
                            <div>
                                @include('layouts.portal.snippets.edit-privacy')
                                <span>
                                    <a href="#">
                                        Done
                                    </a>
                                </span>
                            </div>
                        </div>

                    </section>

                    <section id="about-tab" class="edit__tab" style="display:none;">

                        <p>Intro to this section</p>

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

            <section id="events-tab" class="edit__tab edit__events scrollbar" style="display:none;">

                add event
    
            </section>

        </section>

    </div>
    
@endsection