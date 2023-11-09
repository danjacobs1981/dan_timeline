@inject('carbon', 'Carbon\Carbon')

<div id="timelineEventCreateEdit">

    <header>

        <ul>
            <li>
                <a href="#event-details-tab">Details</a>
            </li>
            <li>
                <a href="#event-map-tab">Map</a>
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
            <li>
                <a href="#event-more-tab">More</a>
            </li>
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
    
                <div class="control control--textbox">
                    <label class="control__label" for="title">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', isset($event) ? $event->title : '') }}"/>
                    <p>The title should reflect this event in just a few words.</p>
                </div>
            
                @include('layouts.portal.snippets.date-picker')

                <div class="control">
                    <span class="control__label">Event Image</span>
                    image
                    <p>The title should reflect your timeline in just a few words. This will also make up your timeline URL.</p>
                </div>
            
                <div class="control control--textarea">
                    <label class="control__label" for="description">Event Description</label>
                    <textarea id="textarea" name="description" rows="4" cols="50"></textarea>
                    <p>This text is revealed once "Read more" is clicked.</p>
                </div>

            </section>

            <section id="event-map-tab" class="event__tab" style="display:none;">

                <div>
                    <h3>Location</h3>
                    <input type="button" value="Drop Pin" id="drop_pin">
                    <div>
                        Show street name (if data available): 
                        <select name="location_geo_street">
                            <option value="0" {{ old('location_geo_street') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('location_geo_street') == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>
                    <div>
                        Show location: 
                        <input type="checkbox" name="location_show" value="1" {{ !old('location_show') || old('location_show') == '1' ? 'checked="checked"' : '' }}>
                    </div>
                    <div id="gmap" style="width: 500px;height:500px;"></div>
                </div>
            
                <div class="hidden" style="display: none;">
                    <h3>Hidden location fields</h3>
                    <div>
                        Latitude: <input type="text" name="location_lat" value="{{ old('location_lat') }}"/>
                    </div>
                    <div>
                        Longitude: <input type="text" name="location_lng" value="{{ old('location_lng') }}"/>
                    </div>
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

            <section id="event-more-tab" class="event__tab" style="display:none;">

                more

            </section>

        </form>
        
    </section>

</div>

@isset($modal)
    @vite('resources/css/portal/timeline/event/create-edit.scss')
    @vite('resources/js/portal/timeline/event/create-edit.js')
@else
    @push('stylesheets')
        @vite('resources/css/portal/timeline/event/create-edit.scss')
    @endpush
    @push('scripts')
        @vite('resources/js/portal/timeline/event/create-edit.js')
    @endpush
@endif
