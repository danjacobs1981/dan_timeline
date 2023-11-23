@extends('layouts.portal.master')

@push('stylesheets')
    @vite('resources/css/portal/timeline/edit.scss')
@endpush
@push('scripts')
    @vite('resources/js/portal/timeline/edit.js')
@endpush

@section('content')

    <meta name="timeline" content="{{ $timeline->id }}">

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
                            <a href="#settings-tab">Settings</a>
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

                        @include('layouts.portal.snippets.edit-settings')

                    </section>

                    <section id="about-tab" class="edit__tab" style="display:none;">

                        <div class="timelineAbout">

                            <div class="control-submit control-submit-sticky">
                                <button data-id="{{ $timeline->id }}" type="submit" class="btn" disabled>Update About</button>
                            </div>

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

                        </div>

                    </section>

                    <section id="tags-tab" class="edit__tab" style="display:none;">

                        <div class="timelineTags">

                            <p>Listing of all tags that can be added to individual events for filtering.</p>

                        </div>

                    </section>

                    <section id="resources-tab" class="edit__tab" style="display:none;">

                        <div class="timelineResources">

                            <p>Listing of all resources that can be added to individual events.</p>

                        </div>

                    </section>

                    <section id="comments-tab" class="edit__tab" style="display:none;">

                        <div class="timelineComments">

                            <p>Moderate comments that have been made on your timeline.</p>

                        </div>

                    </section>

                    <section id="collaborators-tab" class="edit__tab" style="display:none;">

                        <div class="timelineCollaborators">

                            <p>Allow others to collaborate with you in building your timeline.</p>

                        </div>

                    </section>

                    <section id="delete-tab" class="edit__tab" style="display:none;">

                        <div class="timelineDelete">

                            <p>Delete your timeline.</p>

                            <div class="control control--textbox">
                                <label class="control__label" for="title">Delete Timeline</label>
                                <a href="{{ route('timelines.delete.showModal', [ 'timeline' => $timeline->id ]) }}" class="btn btn-danger" data-modal data-modal-class="modal-timeline-delete" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">
                                    <i class="fa-regular fa-trash-can"></i>Delete
                                </a>                            
                                <p>Deleting a timeline cannot be undone.</p>
                            </div>

                        </div>

                    </section>

                </section>

            </div>

            <section id="events-tab" class="edit__tab edit__events" style="display:none;">

                <div class="timelineEvents">

                    <div class="loading">
                        <div class="dots"><div></div><div></div><div></div><div></div></div>
                    </div>
    
                    <header>
                        <div>
                            <span>Loading events...</span>
                            <em><i class="fa-regular fa-square-caret-right"></i>Expand all dates</li></em>
                        </div>
                        <a href="{{ route('timelines.events.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                            <i class="fa-solid fa-circle-plus"></i>Add Event
                        </a>
                    </header>
    
                    <div class="control-submit">
                        <a href="{{ route('timelines.events.create', [ 'timeline' => $timeline->id ]) }}" class="btn btn-outline" data-modal data-modal-full data-modal-scroll data-modal-class="modal-create-edit-event" data-modal-size="modal-xl" data-modal-showclose="false" data-modal-clickclose="false">
                            <i class="fa-solid fa-circle-plus"></i>Add Event
                        </a>
                    </div>
                    
                    <section id="events" class="sortable scrollbar"></section>    

                </div>

            </section>

        </section>

    </div>

    
    
@endsection