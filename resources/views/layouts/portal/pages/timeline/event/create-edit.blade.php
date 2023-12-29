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
                <a href="#event-sources-tab">Sources</a>
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

                    <p>Details about the event.</p>
    
                    <div class="control control--textbox">
                        <label class="control__label" for="title">Event Title</label>
                        <input type="text" name="title" value="{{ old('title', isset($event) ? $event->title : '') }}"/>
                        <p>The title should reflect this event in just a few words.</p>
                    </div>
                
                    @include('layouts.portal.snippets.form-date', [ 'date' => false, 'timezone' => isset($event) ? $event->location_tz : null ])

                    <div class="control control--image control--select {{ isset($event->image) ? 'control--image-exists' : '' }}">
                        <label class="control__label" for="image">Event Image</label>
                        <input type="file" id="image" name="image" accept=".jpeg, .png, .jpg, .gif">
                        <input type="hidden" name="image_delete" value="0">
                        <div class="image-preview">
                            <a href="#" class="btn btn-danger">
                                Remove / Replace Image
                            </a>
                            <div>
                                <div class="thumbnail">
                                    <strong>
                                        Thumbnail Version
                                    </strong>
                                    <div class="input-event-image" style="{{ isset($event->image) ? 'background-image:url('.asset('storage/images/timeline/'.$event->timeline_id.'/'.$event->id.'/'.$event->image).');background-position:'.$event->image_thumbnail.';' : '' }}"></div>
                                </div>
                            </div>
                            <select name="image_thumbnail" data-image="thumbnail">
                                <option data-type="tall" value="50% 0" {{ old('image_thumbnail') == '50% 0' || (isset($event) && $event->image_thumbnail == '50% 0') ? 'selected' : '' }}>Top</option>
                                <option data-type="tall" value="50% 50%" {{ old('image_thumbnail') == '50% 50%' || (isset($event) && $event->image_thumbnail == '50% 50%') ? 'selected' : '' }}>Middle</option>
                                <option data-type="tall" value="50% 100%" {{ old('image_thumbnail') == '50% 100%' || (isset($event) && $event->image_thumbnail == '50% 100%') ? 'selected' : '' }}>Bottom</option>
                                <option data-type="wide" value="0 50%" {{ old('image_thumbnail') == '0 50%' || (isset($event) && $event->image_thumbnail == '0 50%') ? 'selected' : '' }}>Left</option>
                                <option data-type="wide" value="50% 50%" {{ old('image_thumbnail') == '50% 50%' || (isset($event) && $event->image_thumbnail == '50% 50%') ? 'selected' : '' }}>Middle</option>
                                <option data-type="wide" value="100% 50%" {{ old('image_thumbnail') == '100% 50%' || (isset($event) && $event->image_thumbnail == '100% 50%') ? 'selected' : '' }}>Right</option>
                            </select>
                            <div>
                                <div class="larger">
                                    <strong>
                                        Larger Version
                                    </strong>
                                    <div class="input-event-image" style="{{ isset($event->image) ? 'background-image:url('.asset('storage/images/timeline/'.$event->timeline_id.'/'.$event->id.'/'.$event->image).');background-position:'.$event->image_large.';' : '' }}"></div>
                                </div>
                            </div>
                            <select name="image_large" data-image="larger">
                                <option value="50% 0" {{ old('image_large') == '50% 0' || (isset($event) && $event->image_large == '50% 0') ? 'selected' : '' }}>Top</option>
                                <option value="50% 50%" {{ old('image_large') == '50% 50%' || (isset($event) && $event->image_large == '50% 50%') ? 'selected' : '' }}>Middle</option>
                                <option value="50% 100%" {{ old('image_large') == '50% 100%' || (isset($event) && $event->image_large == '50% 100%') ? 'selected' : '' }}>Bottom</option>
                            </select>
                        </div>
                    </div>

                    <div class="control control--textarea">
                        <label class="control__label" for="description">Event Description</label>
                        <textarea id="description" name="description" rows="4" cols="50">{{ old('description', isset($event) ? $event->description : '') }}</textarea>
                        <p>This text is revealed once "Read more" is clicked.</p>
                        <p>New lines are converted to paragraphs.</p>
                        <p>HTML tags are not allowed and are automatically removed.</p>
                    </div>

                </div>

            </section>

            <section id="event-map-tab" class="event__tab" style="display:none;">

                <div class="eventMap">

                    <p>Choose whether to add a marker on the map for the event.</p>

                    @include('layouts.portal.snippets.form-location', [ 'date' => false ])

                </div>

            </section>
        
            <section id="event-sources-tab" class="event__tab" style="display:none;">

                @include('layouts.portal.snippets.edit-sources', [ 'placement' => 'event' ])

            </section>

            <section id="event-tags-tab" class="event__tab" style="display:none;">

                <div class="eventTags">

                    <p>Tag the event so it can be easily filtered.</p>

                    

                </div>
            </section>

            <section id="event-comments-tab" class="event__tab" style="display:none;">

                <div class="eventComments">

                    <p>Moderate any comments that have been left on the event.</p>

                    <p>todo: Note: commenting has been turned off for all events.</p>

                    <p>todo: Give options to turn on/off comments here.</p>

                </div>
            </section>

            @if(isset($event))
                <section id="event-delete-tab" class="event__tab" style="display:none;">

                    <div class="eventDelete">

                        <p>Delete the event.</p>

                        <div class="control control--textbox">
                            <label class="control__label" for="title">Delete Event</label>
                            <a href="{{ route('timelines.events.delete.showModal', [ 'timeline' => $timeline->id, 'event' => $event->id ]) }}" class="btn btn-danger" data-modal data-modal-class="modal-timeline-event-delete modal-delete" data-modal-size="modal-sm" data-modal-showclose="false" data-modal-clickclose="false">
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
