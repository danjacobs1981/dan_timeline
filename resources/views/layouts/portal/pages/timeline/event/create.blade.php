<div id="timelineEventCreate">

    <h2>Add Event</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div>
    @endif

    <form method="post" action="{{ route('timelines.events.store', [ 'timeline' => $timeline ]) }}">

        <!--@csrf-->

        <div class="control control--textbox">
            <label class="control__label" for="title">Event Title</label>
            <input type="text" name="title" value="{{ old('title') }}"/>
            <p>The title should reflect your timeline in just a few words. This will also make up your timeline URL.</p>
        </div>
    
        <div class="date_wrapper">
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

        <hr/>
    
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

        <hr/>
    
        <div>
            <button type="submit" class="btn btn-primary">Add Event</button>
        </div>
    
    </form>

</div>

@vite('resources/js/portal/timeline/event/create.js')