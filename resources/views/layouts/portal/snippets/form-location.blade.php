@php
    $location_zoom = null;
    $location_geo = null;
    if (isset($event)) {
        $location_zoom = $event->location_zoom;
        $location_geo = $event->location_geo;
    }
@endphp

<input type="hidden" name="location_show" value="{{ old('location_show', isset($event) ? $event->location_show : 0) }}"/>

<div class="control control--radio">
    <span class="control__label">Map Options</span>
    <label class="control__label">Do <strong>not</strong> add a marker to the timeline map for this event
        <input type="radio" value="0" name="location_show_picker" />
        <div></div>
    </label>
    <label class="control__label">Add a marker to the map for this event (this also provides a timezone)
        <input type="radio" value="1" name="location_show_picker" />
        <div></div>
    </label>
    <label class="control__label">Add a marker to the map to <strong>only</strong> provide a timezone (the marker will not appear on the timeline map)
        <input type="radio" value="2" name="location_show_picker" />
        <div></div>
    </label>
</div>

<div class="eventMap-map">

    <div class="control control--textbox">
        <label class="control__label" for="autocomplete">Enter a location below to drop a marker or <em class="drop_marker">drop a marker onto the center of the map viewport</em></label>
        <input id="autocomplete" type="text" placeholder="Start typing a location..." />
        <p>Plot the location of the event / timezone by positioning a marker on the map.</p>
        <p class="info"><i class="fa-solid fa-circle-info"></i><span>A marker can be dragged / moved once dropped onto the map. <em class="drop_marker">Drop Marker</em></span></p>
        <div id="gmap"></div>
    </div>

    <div class="eventMap-map-options">

        <div class="split">

            <div class="control control--radio">
                <span class="control__label">What address level does this marker best represent?</span>
                <label class="control__label">Building / Premise
                    <input type="radio" value="1" data-zoom="19" name="location_geo" {{ old('location_geo') == '1' || ($location_geo == '1') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <label class="control__label">Street / Road
                    <input type="radio" value="2" data-zoom="18" name="location_geo" {{ old('location_geo') == '2' || ($location_geo == '2') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <label class="control__label">Town / City
                    <input type="radio" value="3" data-zoom="11" name="location_geo" {{ old('location_geo') == '3' || ($location_geo == '3') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <label class="control__label">Region / County / State
                    <input type="radio" value="4" data-zoom="8" name="location_geo" {{ old('location_geo') == '4' || ($location_geo == '4') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <label class="control__label">Country
                    <input type="radio" value="5" data-zoom="4" name="location_geo" {{ old('location_geo') == '5' || ($location_geo == '5') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <p>Address information will be sourced from the marker position and level set. Buildings, streets and roads display a marker, whereas anything further out displays a pin.</p>
            </div>

        </div>

        <!--<p>To do: add marker choice, marker color and streetview screenshots</p>-->

    </div>

    <input type="hidden" name="location_tz" value="{{ old('location_tz', isset($event) ? $event->location_tz : '') }}" />
    <input type="hidden" name="location_zoom" value="{{ old('location_zoom', isset($event) ? $event->location_zoom : '') }}" />
    <input type="hidden" name="location_lat" value="{{ old('location_lat', isset($event) ? $event->location_lat : '') }}" />
    <input type="hidden" name="location_lng" value="{{ old('location_lng', isset($event) ? $event->location_lng : '') }}" />

</div>  