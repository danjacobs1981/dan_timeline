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

            <section id="event-details-tab" class="event__tab" style="display:none;">
    
                <div class="control control--textbox">
                    <label class="control__label" for="title">Event Title</label>
                    <input type="text" name="title" value="{{ old('title', isset($event) ? $event->title : '') }}"/>
                    <p>The title should reflect this event in just a few words.</p>
                </div>
            
                <div class="control control--datepicker" data-predate="">
                    <span class="control__label">Event Date &amp; Time</span>
                    <div>
                        <div class="period year add">
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
                                <input type="text" id="dp_year" value="{{ old('date_year') }}" placeholder="YYYY"/>
                                <span data-popover="Remove year" data-popover-position="bottom">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </span>
                            </div>
                        </div>
                        <div class="period month">
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
                                <select id="dp_month">
                                    <option value="01" {{ old('date_month') == '01' ? 'selected' : '' }}>January</option>
                                    <option value="02" {{ old('date_month') == '02' ? 'selected' : '' }}>February</option>
                                    <option value="03" {{ old('date_month') == '03' ? 'selected' : '' }}>March</option>
                                    <option value="04" {{ old('date_month') == '04' ? 'selected' : '' }}>April</option>
                                    <option value="05" {{ old('date_month') == '05' ? 'selected' : '' }}>May</option>
                                    <option value="06" {{ old('date_month') == '06' ? 'selected' : '' }}>June</option>
                                    <option value="07" {{ old('date_month') == '07' ? 'selected' : '' }}>July</option>
                                    <option value="08" {{ old('date_month') == '08' ? 'selected' : '' }}>August</option>
                                    <option value="09" {{ old('date_month') == '09' ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ old('date_month') == '10' ? 'selected' : '' }}>October</option>
                                    <option value="11" {{ old('date_month') == '11' ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ old('date_month') == '12' ? 'selected' : '' }}>December</option>
                                </select>
                                <span data-popover="Remove month" data-popover-position="bottom">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </span>
                            </div>
                        </div>
                        <div class="period day">
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
                                <select id="dp_day">
                                    <option value="01" {{ old('date_day') == '01' ? 'selected' : '' }}>1st</option>
                                    <option value="02" {{ old('date_day') == '02' ? 'selected' : '' }}>2nd</option>
                                    <option value="30" {{ old('date_day') == '30' ? 'selected' : '' }}>30th</option>
                                </select>
                                <span data-popover="Remove day" data-popover-position="bottom">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </span>
                            </div>
                        </div>
                        <div class="period time">
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
                                    <select id="dp_time_hour">
                                        <option value="01" {{ old('date_time') == '01' ? 'selected' : '' }}>1</option>
                                        <option value="02" {{ old('date_time') == '02' ? 'selected' : '' }}>2</option>
                                        <option value="30" {{ old('date_time') == '30' ? 'selected' : '' }}>12</option>
                                    </select>
                                    :
                                    <select id="dp_time_min">
                                        <option value="01" {{ old('date_time') == '01' ? 'selected' : '' }}>00</option>
                                        <option value="01" {{ old('date_time') == '01' ? 'selected' : '' }}>01</option>
                                        <option value="02" {{ old('date_time') == '02' ? 'selected' : '' }}>02</option>
                                        <option value="30" {{ old('date_time') == '30' ? 'selected' : '' }}>59</option>
                                    </select>
                                    <select id="dp_time_ap">
                                        <option value="01" {{ old('date_time') == '01' ? 'selected' : '' }}>am</option>
                                        <option value="02" {{ old('date_time') == '02' ? 'selected' : '' }}>pm</option>
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

                <div class="date_wrapper" data-predate="{{ isset($predate) ? $predate : '' }}">
                    <h3>Date</h3>
                    <span class="add" data-period="year">Add Date</span>
                    <div class="date">
                        <div class="year {{ old('date_year') || old('date_month') || old('date_day') || old('date_time') ? 'date_active' : '' }}">
                            <label>Year: <span class="remove" data-period="year"><i class="fa-solid fa-xmark"></i></span></label>
                            <input data-date type="text" id="year" value="{{ old('date_year') }}"/>
                            <div>
                                <span class="add" data-period="month">Add Month</span>
                            </div>
                            <div class="month {{ old('date_month') || old('date_day') || old('date_time') ? 'date_active' : '' }}">
                                <label>Month: <span class="remove" data-period="month"><i class="fa-solid fa-xmark"></i></span></label>
                                <select data-date id="month">
                                    <option value="">Select...</option>
                                    <option value="01" {{ old('date_month') == '01' ? 'selected' : '' }}>January</option>
                                    <option value="02" {{ old('date_month') == '02' ? 'selected' : '' }}>February</option>
                                    <option value="03" {{ old('date_month') == '03' ? 'selected' : '' }}>March</option>
                                    <option value="04" {{ old('date_month') == '04' ? 'selected' : '' }}>April</option>
                                    <option value="05" {{ old('date_month') == '05' ? 'selected' : '' }}>May</option>
                                    <option value="06" {{ old('date_month') == '06' ? 'selected' : '' }}>June</option>
                                    <option value="07" {{ old('date_month') == '07' ? 'selected' : '' }}>July</option>
                                    <option value="08" {{ old('date_month') == '08' ? 'selected' : '' }}>August</option>
                                    <option value="09" {{ old('date_month') == '09' ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ old('date_month') == '10' ? 'selected' : '' }}>October</option>
                                    <option value="11" {{ old('date_month') == '11' ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ old('date_month') == '12' ? 'selected' : '' }}>December</option>
                                </select>
                                <div>
                                    <span class="add" data-period="day">Add Day</span>
                                </div>
                                <div class="day {{ old('date_day') || old('date_time') ? 'date_active' : '' }}">
                                    <label>Day: <span class="remove" data-period="day"><i class="fa-solid fa-xmark"></i></span></label>
                                    <input data-date type="text" id="day" value="{{ old('date_day') }}"/>
                                    <div>
                                        <span class="add" data-period="time">Add Time</span>
                                    </div>
                                    <div class="time {{ old('date_time') ? 'date_active' : '' }}">
                                        <label>Time: (XX:XX) <span class="remove" data-period="time"><i class="fa-solid fa-xmark"></i></span></label>
                                        <input data-date type="text" id="time" value="{{ old('date_time') }}"/>
                                        <select data-date id="time_ampm">
                                            <option value="AM" {{ old('date_time_ampm') == 'AM' ? 'selected' : '' }}>am</option>
                                            <option value="PM" {{ old('date_time_ampm') == 'PM' ? 'selected' : '' }}>pm</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden" style="display: none;">
                    <h3>Hidden date fields</h3>
                    <div class="year">
                        Year: <input readonly type="text" name="date_year" /><br/><br/>
                        <div class="month">
                            Month: <input readonly type="text" name="date_month" /><br/><br/>
                            <div class="day">
                                Day: <input readonly type="text" name="date_day" /><br/><br/>     
                                <div class="time">
                                    Time: <input readonly type="text" name="date_time" /><br/><br/>
                                    AM / PM: <input readonly type="text" name="date_time_ampm" />
                                </div>
                            </div>
                        </div>
                    </div>
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
