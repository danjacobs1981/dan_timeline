@inject('carbon', 'Carbon\Carbon')

@if(isset($event))
    <meta name="event" content="{{ $event->id }}">
@endif

<div id="timelineEventCreateEdit">

    <header>

        <ul class="tabs tabs--event">
            <li>
                <a href="#event-details-tab">Details</a>
            </li>
            <li>
                <a href="#event-map-tab">Map / Location</a>
            </li>
            <li>
                <a href="#event-resources-tab">Resources</a>
            </li>
            <li>
                <a href="#event-tags-tab">Tags / Filters</a>
            </li>
            <li>
                <a href="#event-comments-tab">Comments</a>
            </li>
            @if(isset($event))
                <li>
                    <a href="#event-delete-tab">Delete</a>
                </li>
            @endif
        </ul>

    </header>

    <section class="scrollbar">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="formEventCreateEdit" method="post" action="{{ isset($event) ? route('timelines.events.update', [ 'timeline' => $timeline, 'event' => $event ]) : route('timelines.events.store', [ 'timeline' => $timeline ]) }}">

            @php
                if (isset($event)) {
                    if ($event->date_type == 1) {
                        $predate = $event->date_year;
                    } else if ($event->date_type == 2) {
                        $predate = $carbon::parse($event->date_unix)->format('Y|n');
                    } else if ($event->date_type == 3) {
                        $predate = $carbon::parse($event->date_unix)->format('Y|n|j');
                    } else if ($event->date_type == 4) {
                        $predate = $carbon::parse($event->date_unix)->format('Y|n|j|h|i|a');
                    }
                }
            @endphp

            @if(isset($event))
                @method('put')
            @endif

            <section id="event-details-tab" class="event__tab" style="display:none;">

                <div class="eventDetails">
    
                    <div class="control control--textbox">
                        <label class="control__label" for="title">Event Title</label>
                        <input type="text" name="title" value="{{ old('title', isset($event) ? $event->title : '') }}"/>
                        <p>The title should reflect this event in just a few words.</p>
                    </div>
                
                    @include('layouts.portal.snippets.form-date', [ 'date' => false, 'timezone' => isset($event) ? $event->location_tz : null ])

                    <!---<div class="control control--image">
                        <span class="control__label">Event Image</span>
                        <input type="file" id="" name="" accept=".jpeg, .png, .jpg">
                        <div>
                            <div class="thumbnail">
                                <strong>
                                    Thumbnail Version
                                </strong>
                                <div style="background-image:url({{ Vite::asset('resources/images/test/cover6.jpg') }});"></div>
                                <p>This thumbnail version of the image appears on the event.</p>
                            </div>
                            <div class="larger">
                                <strong>
                                    Larger Version
                                </strong>
                                <div style="background-image:url({{ Vite::asset('resources/images/test/cover6.jpg') }});"></div>
                                <p>The larger version of the image appears once "Read more" is clicked.</p>
                            </div>
                        </div>
                    </div>--->

                    <div class="control control--textarea">
                        <label class="control__label" for="description">Event Description</label>
                        <textarea id="textarea" name="description" rows="4" cols="50"></textarea>
                        <p>This text is revealed once "Read more" is clicked.</p>
                    </div>

                </div>

            </section>

            <section id="event-map-tab" class="event__tab" style="display:none;">

                <div class="eventMap">

                    @include('layouts.portal.snippets.form-location', [ 'date' => false ])

                </div>

            </section>
        
            <section id="event-resources-tab" class="event__tab" style="display:none;">

                resources

            </section>

            <section id="event-tags-tab" class="event__tab" style="display:none;">

                tags

            </section>

            <section id="event-comments-tab" class="event__tab" style="display:none;">

                comments

            </section>

            @if(isset($event))
                <section id="event-delete-tab" class="event__tab" style="display:none;">

                    <div class="eventDelete">

                        <p>Delete this event.</p>

                        <div class="control control--textbox">
                            <label class="control__label" for="title">Delete Event</label>
                            <a href="{{ route('timelines.events.delete.showModal', [ 'timeline' => $timeline->id, 'event' => $event->id ]) }}" class="btn btn-danger" data-modal data-modal-class="modal-timeline-event-delete" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">
                                <i class="fa-regular fa-trash-can"></i>Delete
                            </a>                            
                            <p>Deleting an event cannot be undone.</p>
                        </div>

                    </div>

                </section>
            @endif

        </form>
        
    </section>

</div>

@isset($modal)
    @vite('resources/css/portal/timeline/event/create-edit.scss')
    @vite('resources/js/portal/timeline/event/create-edit.js')
    @vite('resources/js/portal/timeline/event/form-date-location.js')
@else
    @push('stylesheets')
        @vite('resources/css/portal/timeline/event/create-edit.scss')
    @endpush
    @push('scripts')
        @vite('resources/js/portal/timeline/event/create-edit.js')
        @vite('resources/js/portal/timeline/event/form-date-location.js')
    @endpush
@endif
