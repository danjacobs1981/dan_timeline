@extends('layouts.portal.master')

@push('stylesheets')
@vite('resources/css/portal/edit.scss')
@endpush
@push('scripts')
    @vite('resources/js/portal/timeline/edit.js')
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
                            <a href="#resources-tab">Resources</a>
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

                        <div class="top">
                            <p>Edit general timeline settings, including which features are shown on your timeline.</p>
                            <div class="visibility">
                                <span data-popover="Change timeline visibility" data-popover-position="bottom">
                                    <em>
                                        Timeline Visibility
                                    </em>
                                    <strong>
                                        @if(old('privacy', $timeline->privacy) === 3)
                                            <i class="fa-regular fa-eye public"></i>Public
                                        @elseif(old('privacy', $timeline->privacy) === 2)
                                            <i class="fa-regular fa-eye"></i>Unlisted
                                        @elseif(old('privacy', $timeline->privacy) === 1)
                                            <i class="fa-regular fa-eye-slash"></i>Private
                                        @else
                                            <i class="fa-brands fa-firstdraft"></i>Draft
                                        @endif
                                    </strong>
                                </span>
                                <div class="visibility-options">
                                    @include('layouts.portal.snippets.edit-privacy')
                                    <span>
                                        <a href="#">
                                            Done
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        @include('layouts.portal.snippets.edit-settings')

                    </section>

                    <section id="about-tab" class="edit__tab" style="display:none;">

                        <p>Give a summary of what your timeline is about.</p>

                        <div class="control control--select">
                            <label class="control__label" for="select">Dropdown</label>
                            <select name="select" id="select">
                                <option value="volvo">Volvo</option>
                                <option value="saab">Saab</option>
                                <option value="mercedes">Mercedes</option>
                                <option value="audi">Audi</option>
                              </select>
                              <p>Helpful line of text goes along here.</p>
                        </div>

                        <div class="control control--textarea">
                            <label class="control__label" for="textarea">Textarea</label>
                            <textarea id="textarea" name="textarea" rows="4" cols="50">At w3schools.com you will learn how to make a website. They offer free tutorials in all web development technologies.</textarea>
                            <p>Helpful line of text goes along here.</p>
                        </div>

                        <div class="control-submit">
                            <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update About</button>
                        </div>

                    </section>

                    <section id="tags-tab" class="edit__tab" style="display:none;">

                        <p>Listing of all tags that can be added to individual events for filtering.</p>

                    </section>

                    <section id="resources-tab" class="edit__tab" style="display:none;">

                        <p>Listing of all resources that can be added to individual events.</p>

                    </section>

                    <section id="comments-tab" class="edit__tab" style="display:none;">

                        <p>Moderate comments that have been made on your timeline.</p>

                    </section>

                    <section id="collaborators-tab" class="edit__tab" style="display:none;">

                        <p>Allow others to collaborate with you in building your timeline.</p>

                    </section>

                    <section id="more-tab" class="edit__tab" style="display:none;">

                        <p>Further timeline settings.</p>

                        <form action="{{ route('timelines.destroy', $timeline->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn">Delete timeline</button>
                        </form>

                    </section>

                </section>

            </div>

            <section id="events-tab" class="edit__tab edit__events" style="display:none;">

                <div class="loading">
                    <div class="dots"><div></div><div></div><div></div><div></div></div>
                </div>

                <header>
                    <div>
                        <span></span>
                        <em><i class="fa-regular fa-square-caret-down"></i>Expand all dates</li></em>
                    </div>
                    <a href="{{ route('timelines.events.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-class="modal-create-event scrollbar" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">
                        <i class="fa-solid fa-circle-plus"></i>Add Event
                    </a>
                </header>

                <div class="control-submit">
                    <a href="{{ route('timelines.events.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-class="modal-create-event scrollbar" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="true">
                        <i class="fa-solid fa-circle-plus"></i>Add Event
                    </a>
                </div>
                
                <section id="events" class="sortable scrollbar"></section>

            </section>

        </section>

    </div>

    
    
@endsection