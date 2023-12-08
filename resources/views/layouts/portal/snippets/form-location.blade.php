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
                    <input type="radio" value="1" name="location_geo" {{ old('location_geo') == '1' || ($location_geo == '1') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <label class="control__label">Street / Road
                    <input type="radio" value="2" name="location_geo" {{ old('location_geo') == '2' || ($location_geo == '2') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <label class="control__label">Town / City
                    <input type="radio" value="3" name="location_geo" {{ old('location_geo') == '3' || ($location_geo == '3') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <label class="control__label">Region / County / State
                    <input type="radio" value="4" name="location_geo" {{ old('location_geo') == '4' || ($location_geo == '4') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <label class="control__label">Country
                    <input type="radio" value="5" name="location_geo" {{ old('location_geo') == '5' || ($location_geo == '5') ? 'checked' : '' }}/>
                    <div></div>
                </label>
                <p>Address information will be sourced from the marker position and level set.</p>
            </div>

            <div class="control control--select">
                <label class="control__label" for="location_zoom">Zoom</label>
                <select name="location_zoom" id="location_zoom">
                    <!--<option value="1" {{ old('location_zoom') == '1' || ($location_zoom == '1') ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('location_zoom') == '2' || ($location_zoom == '2') ? 'selected' : '' }}>2</option>-->
                    <option value="3" {{ old('location_zoom') == '3' || ($location_zoom == '3') ? 'selected' : '' }}>3</option>
                    <option value="4" {{ old('location_zoom') == '4' || ($location_zoom == '4') ? 'selected' : '' }}>4</option>
                    <option value="5" {{ old('location_zoom') == '5' || ($location_zoom == '5') ? 'selected' : '' }}>5</option>
                    <option value="6" {{ old('location_zoom') == '6' || ($location_zoom == '6') ? 'selected' : '' }}>6</option>
                    <option value="7" {{ old('location_zoom') == '7' || ($location_zoom == '7') ? 'selected' : '' }}>7</option>
                    <option value="8" {{ old('location_zoom') == '8' || ($location_zoom == '8') ? 'selected' : '' }}>8</option>
                    <option value="9" {{ old('location_zoom') == '9' || ($location_zoom == '9') ? 'selected' : '' }}>9</option>
                    <option value="10" {{ old('location_zoom') == '10' || ($location_zoom == '10') ? 'selected' : '' }}>10</option>
                    <option value="11" {{ old('location_zoom') == '11' || ($location_zoom == '11') ? 'selected' : '' }}>11</option>
                    <option value="12" {{ old('location_zoom') == '12' || ($location_zoom == '12') ? 'selected' : '' }}>12</option>
                    <option value="13" {{ old('location_zoom') == '13' || ($location_zoom == '13') ? 'selected' : '' }}>13</option>
                    <option value="14" {{ old('location_zoom') == '14' || ($location_zoom == '14') ? 'selected' : '' }}>14</option>
                    <option value="15" {{ old('location_zoom') == '15' || ($location_zoom == '15') ? 'selected' : '' }}>15</option>
                    <option value="16" {{ old('location_zoom') == '16' || ($location_zoom == '16') ? 'selected' : '' }}>16</option>
                    <option value="17" {{ old('location_zoom') == '17' || ($location_zoom == '17') ? 'selected' : '' }}>17</option>
                    <option value="18" {{ old('location_zoom') == '18' || ($location_zoom == '18') ? 'selected' : '' }}>18</option>
                    <option value="19" {{ old('location_zoom') == '19' || ($location_zoom == '19') ? 'selected' : '' }}>19</option>
                </select>
            </div>

        </div>

        <!--<p>To do: add marker choice, marker colour and streetview screenshots</p>-->

    </div>

    <input type="hidden" name="location_tz" value="{{ old('location_tz', isset($event) ? $event->location_tz : '') }}" />

    <input type="hidden" name="location_lat" value="{{ old('location_lat', isset($event) ? $event->location_lat : '') }}" />
    <input type="hidden" name="location_lng" value="{{ old('location_lng', isset($event) ? $event->location_lng : '') }}" />

</div>  