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

        <form id="formEventCreateEdit" method="post" action="{{ isset($event) ? route('timelines.events.update', [ 'timeline' => $timeline, 'event' => $event ]) : route('timelines.events.store', [ 'timeline' => $timeline ]) }}">

            <section id="event-details-tab" class="event__tab" style="display:none;">
    
                <div class="control control--textbox">
                    <label class="control__label" for="title">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', isset($event) ? $event->title : '') }}"/>
                    <p>The title should reflect this event in just a few words.</p>
                </div>
            
                <div class="control control--datepicker" data-predate="{{ isset($predate) ? $predate : '' }}">
                    <span class="control__label">Event Date &amp; Time</span>
                    <div>
                        <div class="period year add" data-period="year">
                            <em data-popover="Add year" data-popover-position="top">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>
                                    Year
                                </span>
                            </em>
                            <div>
                                <strong>
                                    Year
                                </strong>
                                <input type="text" data-date id="year" value="{{ old('date_year') }}" placeholder="YYYY" autocomplete="off" />
                                <span data-popover="Remove year" data-popover-position="bottom">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </span>
                            </div>
                        </div>
                        <div class="period month" data-period="month">
                            <em data-popover="Add month" data-popover-position="top">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>
                                    Month
                                </span>
                            </em>
                            <div>
                                <strong>
                                    Month
                                </strong>
                                <select id="month" data-date>
                                    <option value="1" {{ old('date_month') == '1' ? 'selected' : '' }}>January</option>
                                    <option value="2" {{ old('date_month') == '2' ? 'selected' : '' }}>February</option>
                                    <option value="3" {{ old('date_month') == '3' ? 'selected' : '' }}>March</option>
                                    <option value="4" {{ old('date_month') == '4' ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ old('date_month') == '5' ? 'selected' : '' }}>May</option>
                                    <option value="6" {{ old('date_month') == '6' ? 'selected' : '' }}>June</option>
                                    <option value="7" {{ old('date_month') == '7' ? 'selected' : '' }}>July</option>
                                    <option value="8" {{ old('date_month') == '8' ? 'selected' : '' }}>August</option>
                                    <option value="9" {{ old('date_month') == '9' ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ old('date_month') == '10' ? 'selected' : '' }}>October</option>
                                    <option value="11" {{ old('date_month') == '11' ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ old('date_month') == '12' ? 'selected' : '' }}>December</option>
                                </select>
                                <span data-popover="Remove month" data-popover-position="bottom">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </span>
                            </div>
                        </div>
                        <div class="period day" data-period="day">
                            <em data-popover="Add day" data-popover-position="top">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>
                                    Day
                                </span>
                            </em>
                            <div>
                                <strong>
                                    Day
                                </strong>
                                <select id="day" data-date>
                                    <option value="1" {{ old('date_day') == '1' ? 'selected' : '' }}>1st</option>
                                    <option value="2" {{ old('date_day') == '2' ? 'selected' : '' }}>2nd</option>
                                    <option value="3" {{ old('date_day') == '3' ? 'selected' : '' }}>3rd</option>
                                    <option value="4" {{ old('date_day') == '4' ? 'selected' : '' }}>4th</option>
                                    <option value="5" {{ old('date_day') == '5' ? 'selected' : '' }}>5th</option>
                                    <option value="6" {{ old('date_day') == '6' ? 'selected' : '' }}>6th</option>
                                    <option value="7" {{ old('date_day') == '7' ? 'selected' : '' }}>7th</option>
                                    <option value="8" {{ old('date_day') == '8' ? 'selected' : '' }}>8th</option>
                                    <option value="9" {{ old('date_day') == '9' ? 'selected' : '' }}>9th</option>
                                    <option value="10" {{ old('date_day') == '10' ? 'selected' : '' }}>10th</option>
                                    <option value="11" {{ old('date_day') == '11' ? 'selected' : '' }}>11th</option>
                                    <option value="12" {{ old('date_day') == '12' ? 'selected' : '' }}>12th</option>
                                    <option value="13" {{ old('date_day') == '13' ? 'selected' : '' }}>13th</option>
                                    <option value="14" {{ old('date_day') == '14' ? 'selected' : '' }}>14th</option>
                                    <option value="15" {{ old('date_day') == '15' ? 'selected' : '' }}>15th</option>
                                    <option value="16" {{ old('date_day') == '16' ? 'selected' : '' }}>16th</option>
                                    <option value="17" {{ old('date_day') == '17' ? 'selected' : '' }}>17th</option>
                                    <option value="18" {{ old('date_day') == '18' ? 'selected' : '' }}>18th</option>
                                    <option value="19" {{ old('date_day') == '19' ? 'selected' : '' }}>19th</option>
                                    <option value="20" {{ old('date_day') == '20' ? 'selected' : '' }}>20th</option>
                                    <option value="21" {{ old('date_day') == '21' ? 'selected' : '' }}>21st</option>
                                    <option value="22" {{ old('date_day') == '22' ? 'selected' : '' }}>22nd</option>
                                    <option value="23" {{ old('date_day') == '23' ? 'selected' : '' }}>23rd</option>
                                    <option value="24" {{ old('date_day') == '24' ? 'selected' : '' }}>24th</option>
                                    <option value="25" {{ old('date_day') == '25' ? 'selected' : '' }}>25th</option>
                                    <option value="26" {{ old('date_day') == '26' ? 'selected' : '' }}>26th</option>
                                    <option value="27" {{ old('date_day') == '27' ? 'selected' : '' }}>27th</option>
                                    <option value="28" {{ old('date_day') == '28' ? 'selected' : '' }}>28th</option>
                                    <option value="29" {{ old('date_day') == '29' ? 'selected' : '' }}>29th</option>
                                    <option value="30" {{ old('date_day') == '30' ? 'selected' : '' }}>30th</option>
                                    <option value="31" {{ old('date_day') == '31' ? 'selected' : '' }}>31st</option>
                                </select>
                                <span data-popover="Remove day" data-popover-position="bottom">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </span>
                            </div>
                        </div>
                        <div class="period time" data-period="time">
                            <em data-popover="Add time" data-popover-position="top">
                                <i class="fa-solid fa-circle-plus"></i>
                                <span>
                                    Time
                                </span>
                            </em>
                            <div>
                                <strong>
                                    Time
                                </strong>
                                <div>
                                    <select id="time" data-date>
                                        <option value="01" {{ old('date_time_hour') == '01' ? 'selected' : '' }}>1</option>
                                        <option value="02" {{ old('date_time_hour') == '02' ? 'selected' : '' }}>2</option>
                                        <option value="03" {{ old('date_time_hour') == '03' ? 'selected' : '' }}>3</option>
                                        <option value="04" {{ old('date_time_hour') == '04' ? 'selected' : '' }}>4</option>
                                        <option value="05" {{ old('date_time_hour') == '05' ? 'selected' : '' }}>5</option>
                                        <option value="06" {{ old('date_time_hour') == '06' ? 'selected' : '' }}>6</option>
                                        <option value="07" {{ old('date_time_hour') == '07' ? 'selected' : '' }}>7</option>
                                        <option value="08" {{ old('date_time_hour') == '08' ? 'selected' : '' }}>8</option>
                                        <option value="09" {{ old('date_time_hour') == '09' ? 'selected' : '' }}>9</option>
                                        <option value="10" {{ old('date_time_hour') == '10' ? 'selected' : '' }}>10</option>
                                        <option value="11" {{ old('date_time_hour') == '11' ? 'selected' : '' }}>11</option>
                                        <option value="12" {{ old('date_time_hour') == '12' ? 'selected' : '' }}>12</option>
                                    </select>
                                    :
                                    <select id="time_min" data-date>
                                        <option value="00" {{ old('date_time_min') == '00' ? 'selected' : '' }}>00</option>
                                        <option value="01" {{ old('date_time_min') == '01' ? 'selected' : '' }}>01</option>
                                        <option value="02" {{ old('date_time_min') == '02' ? 'selected' : '' }}>02</option>
                                        <option value="59" {{ old('date_time_min') == '59' ? 'selected' : '' }}>59</option>
                                    </select>
                                    <select id="time_ampm" data-date>
                                        <option value="am" {{ old('date_time_ampm') == 'am' ? 'selected' : '' }}>am</option>
                                        <option value="pm" {{ old('date_time_ampm') == 'pm' ? 'selected' : '' }}>pm</option>
                                    </select>
                                </div>
                                <span data-popover="Remove time" data-popover-position="bottom">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <p>Adding a date is optional. Complete as much (or as little) of the date as required.</p>
                </div>

                <div class="hidden-dates">
                    <input type="hidden" name="date_year" />
                    <input type="hidden" name="date_month" />
                    <input type="hidden" name="date_day" />
                    <input type="hidden" name="date_time" />
                    <input type="hidden" name="date_time_ampm" />
                </div>

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
