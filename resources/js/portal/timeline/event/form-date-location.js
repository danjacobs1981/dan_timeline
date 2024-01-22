import $ from 'jquery';
import { Loader } from '@googlemaps/js-api-loader';

FormDateLocation();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && (modal['options']['modalClass'].includes('modal-create-edit-event') || modal['options']['modalClass'].includes('modal-edit-event-date'))) {
        //console.log("DATE MODAL");
        FormDateLocation();
        //event.stopImmediatePropagation();
    }
});

function FormDateLocation() {

    // DATE PICKER 

    var lat = $('input[name="location_lat"]').val();
    var lng = $('input[name="location_lng"]').val();

    var $datepicker = $('.control--datepicker');
    var predate = $datepicker.data('predate').toString();
    var daysInMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    $.each(predate.split(/\|/), function(i, val) {
        var period = '';
        if (val) {
            if (i == 0) {
                period = 'year';
            } else if (i == 1) {
                period = 'month';
            } else if (i == 2) {
                period = 'day';
            } else if (i == 3) {
                period = 'time';
            } else if (i == 4) {
                period = 'time_min';
            } else if (i == 5) {
                period = 'time_ampm';
            }
            if (i <= 3) {
                $datepicker.find('.period.' + period).removeClass('add').addClass('active').next('div').addClass('add');
            }
            $('#' + period).val(val);
            updateDateFields(period, val);
        }
    });

    $datepicker.on('click', '.period.add', function() { // add
        $(this).addClass('active').removeClass('add').next('div').addClass('add');
        updateDateFields($(this).data('period'), $(this).find('[data-date').val());
    });

    $datepicker.on('click', '.period.active>div>span', function() { // remove
        $(this).closest('.period').css('border-color', 'inherit').removeClass('active').addClass('add').nextAll('div').css('border-color', 'inherit').removeClass('active').removeClass('add');
        var period = $(this).closest('.period').data('period');
        $datepicker.find('p.info').hide();
        $('.date-fields input[name="date_' + period + '"]').val(null).nextAll('input').val(null);
    });

    $datepicker.on('mouseenter', '.period.active>div>span', function() {
        $(this).closest('.period').css('border-color', 'red').nextAll('div.active').css('border-color', 'red');
    });

    $datepicker.on('mouseleave', '.period.active>div>span', function() {
        $('.period').css('border-color', 'inherit');
    });

    $datepicker.on('change', '#month', function() {
        var daysCount = daysInMonth[(this.value * 1) - 1];
        var dayCurrent = $('#day').val();
        $('#day option').show();
        if (daysCount == 30) {
            if (dayCurrent == 31) {
                $('#day, input[name="date_day"]').val(30);
            }
            $('#day option[value=31]').hide();
        } else if (daysCount == 29) {
            if (dayCurrent > 29) {
                $('#day, input[name="date_day"]').val(29);
            }
            $('#day option[value=31], #day option[value=30]').hide();
        }
    });

    $datepicker.on('input', '#year', function() {
        updateDateFields('year', this.value);
    });

    $datepicker.on('change', 'select', function() {
        updateDateFields($(this).attr('id'), this.value);
    });

    function updateDateFields(period, value) {
        if (period == 'year') {
            $('input[name="date_year"]').val(value);
        } else if (period == 'time' || period == 'time_min') {
            $('input[name="date_time"]').val($('#time').val() + ':' + $('#time_min').val());
            $('input[name="date_time_ampm"]').val($('#time_ampm').val());
            if ($('input[name="location_show"]').val() == 0) {
                setInfoTimezone(false);
            } else {
                setInfoTimezone(true);
            }
        } else {
            $('input[name="date_' + period + '"]').val(value);
        }
    }

    /* map */

    var mapLoaded = 0;
    var mapTab = 0;

    if (!hasInputValue('location_lat')) {
        $('input:radio[name="location_show_picker"][value="0"]').prop('checked', true);
        $('input[name="location_show"]').val(0);
    }

    $('input:radio[name="location_show_picker"][value="' + getLocationShow() + '"]').prop('checked', true);

    $('a[href="#event-map-tab"]').on('click', function() {
        if (!mapTab) {
            updateMapOptions(getLocationShow());
            mapTab = 1;
        }
    });

    $('input:radio[name="location_show_picker"]').on('change', function() {
        updateMapOptions($(this).val());
    });

    function updateMapOptions(value) {
        if (value > 0) {
            if (!mapLoaded) {
                loadMap();
            }
            $('.eventMap-map').show();
            if (hasInputValue('location_lat')) {
                setInfoTimezone(true);
                if (value == 1) {
                    if (!$('input:radio[name="location_geo"]').is(":checked")) {
                        $('input:radio[name="location_geo"][value="3"]').prop('checked', true);
                    }
                    $('.eventMap-map-options').show();
                } else {
                    $('.eventMap-map-options').hide();
                }
            }
        } else {
            $('.eventMap-map').hide();
            setInfoTimezone(false);
        }
        $('input[name="location_show"]').val(value);
    }

    function getLocationShow() {
        return $('input[name="location_show"]').val();
    }

    function hasInputValue(name) {
        if ($('input[name="' + name + '"]').val() != '') {
            return true;
        } else {
            return false;
        }
    }

    function changedLatLng() {
        if ($('input[name="location_lat"]').val() != lat || $('input[name="location_lng"]').val() != lng) {
            return true;
        } else {
            return false;
        }
    }

    function setZoomLevel(zoom) {
        var value = 5;
        if (zoom >= 17) {
            value = 1;
        } else if (zoom >= 15) {
            value = 2;
        } else if (zoom >= 10) {
            value = 3;
        } else if (zoom >= 8) {
            value = 4;
        }
        $('input:radio[name="location_geo"][value="' + value + '"]').prop('checked', true);
        $('select[name="location_zoom"]').val(Math.round(zoom));
    }

    function setInfoTimezone(info) {
        if ($('input[name="date_time"]').val()) {
            $datepicker.find('p.info').hide();
            if (!info) {
                $datepicker.find('p.info[data-current="none"]').show();
            } else {
                if (hasInputValue('location_tz') && !changedLatLng()) {
                    $datepicker.find('p.info[data-current="timezone"]').show();
                } else if (hasInputValue('location_lat')) {
                    $datepicker.find('p.info[data-current="location"]').show();
                } else {
                    $datepicker.find('p.info[data-current="none"]').show();
                }
            }
        }
    }

    function loadMap() {

        mapLoaded = 1;

        let map;
        var mapCenter = { lat: 0, lng: 0 };
        var mapZoom = 3;

        var marker;

        if (hasInputValue('location_lat')) {
            mapCenter = { lat: parseFloat($('input[name="location_lat"]').val()), lng: parseFloat($('input[name="location_lng"]').val()) };
            mapZoom = parseInt($('select[name="location_zoom"]').val());
        }

        const loader = new Loader({
            apiKey: import.meta.env.VITE_GOOGLE_API,
            version: 'beta',
            libraries: ['places']
        });

        const mapOptions = {
            center: mapCenter,
            zoom: mapZoom,
            maxZoom: 19,
            minZoom: 1,
            mapId: '53cedd9afde08104',
            mapTypeId: 'hybrid',
            options: {
                gestureHandling: 'greedy',
                streetViewControl: false,
            },
            restriction: {
                latLngBounds: {
                    east: 179.9999,
                    north: 85,
                    south: -85,
                    west: -179.9999
                },
                strictBounds: true
            }
        };

        loader.importLibrary('maps').then(({ Map }) => {

                map = new Map(document.getElementById('gmap'), mapOptions);

                const options = {
                    fields: ['geometry'],
                    strictBounds: false,
                };

                const input = document.getElementById('autocomplete');
                const autocomplete = new google.maps.places.Autocomplete(input, options);

                const label = {
                    fontFamily: 'Fontawesome',
                    fontSize: '40px',
                    color: 'red',
                    text: '\uf3c5'
                };

                const icon = {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 20,
                    anchor: new google.maps.Point(0, 1), // bottom of circle
                    strokeWeight: 0
                };

                marker = new google.maps.Marker({
                    map,
                    map,
                    draggable: true,
                    label: label,
                    icon: icon
                });

                if (hasInputValue('location_lat')) {
                    //console.log(mapCenter);
                    marker.setPosition(mapCenter);
                    marker.setVisible(true);
                }

                map.addListener('zoom_changed', () => {
                    $('select[name="location_zoom"]').val(Math.round(map.getZoom()));
                });

                google.maps.event.addListener(marker, 'dragend', function() {
                    setLatLng(marker);
                    setInfoTimezone(true);
                });

                autocomplete.addListener('place_changed', () => {

                    marker.setVisible(false);

                    const place = autocomplete.getPlace();

                    if (!place.geometry || !place.geometry.location) {
                        // User entered the name of a Place that was not suggested and
                        // pressed the Enter key, or the Place Details request failed.
                        window.alert('No details available for this location');
                        return;
                    }

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }

                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                    //console.log(place.address_components);

                    $('#autocomplete').val('');

                    setZoomLevel(map.getZoom());
                    setLatLng(marker);

                    setInfoTimezone(true);

                    if (getLocationShow() == 1) {
                        $('.eventMap-map-options').show();
                    }

                });

                $('select[name="location_zoom"]').on('change', function() {
                    map.setZoom(parseInt($(this).val()));
                });

                $('.drop_marker').on('click', function() {
                    dropMarker();
                });

                function dropMarker() {
                    if (marker) {
                        marker.setMap(null);
                    }
                    marker = new google.maps.Marker({
                        position: map.getCenter(),
                        map,
                        draggable: true,
                        label: label,
                        icon: icon
                    });
                    setLatLng(marker);
                    if (getLocationShow() == 1) {
                        if (!$('input:radio[name="location_geo"]').is(":checked")) {
                            setZoomLevel(parseInt($('select[name="location_zoom"]').val()));
                        }
                        $('.eventMap-map-options').show();
                    }
                    setInfoTimezone(true);
                    google.maps.event.addListener(marker, 'dragend', function() {
                        setLatLng(marker);
                        setInfoTimezone(true);
                    });
                }

                function setLatLng(marker) {
                    $('input[name="location_lat"]').val(marker.getPosition().lat());
                    $('input[name="location_lng"]').val(marker.getPosition().lng());
                }

            })
            .catch((e) => {
                // do something
            });

    }


}