import $ from 'jquery';
import { Loader } from '@googlemaps/js-api-loader';
import { loadEvents } from './../edit.js';

runScript();

$(document).on($.modal.OPEN, function() {
    runScript();
});

function runScript() {

    /* map */

    let map;
    var endMarker;

    const loader = new Loader({
        apiKey: "AIzaSyCdUgGxKLmMPrK3dwRdBq2XHFWXWpXnrrM",
        version: "weekly",
        libraries: ["places"]
    });
    const mapOptions = {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8,
        mapId: "53cedd9afde08104",
        mapTypeId: "roadmap",
        disableDefaultUI: true,
        options: {
            gestureHandling: 'greedy'
        }
    };
    loader
        .importLibrary('maps')
        .then(({ Map }) => {
            map = new Map(document.getElementById("gmap"), mapOptions);
        })
        .catch((e) => {
            // do something
        });

    $('#drop_pin').on('click', function() {
        dropPin();
    });

    $('[data-date]').each(function() {
        var name = $(this).attr('id');
        $('input[name="date_' + name + '"]').val(this.value);
        if (this.value) {
            $('span.add[data-period="' + name + '"]').hide();
            if (name == 'time') {
                $('span.add[data-period]').hide();
            } else if (name == 'day') {
                $('span.add[data-period="month"], span.add[data-period="year"]').hide();
            } else if (name == 'month') {
                $('span.add[data-period="year"]').hide();
            }
        }
    });

    $('.date_wrapper span[data-period]').on('click', function() {
        var name = $(this).data('period');
        if ($(this).hasClass('add')) {
            $(this).hide();
            $('div.' + name).addClass('date_active');
            if (name == 'month') {
                $('.hidden div.month>input').val($('select[id="month"]').val());
            } else if (name == 'time') {
                $('.hidden div.time>input[name="date_time"]').val($('input[id="time"]').val());
                $('.hidden div.time>input[name="date_time_ampm"]').val($('select[id="time_ampm"]').val());
            } else {
                $('.hidden div.' + name + '>input').val($('input[id="' + name + '"]').val());
            }
        } else if ($(this).hasClass('remove')) {
            $('span.add[data-period="' + name + '"], .date div.' + name + ' span[data-period].add').show();
            $('div.' + name + ', div.' + name + ' .date_active').removeClass('date_active');
            $('.hidden div.' + name + ' input, .hidden div.' + name + ' div.date_active input').val('');
        }
    });

    $('.date input').on('input', function() {
        var name = $(this).attr('id');
        $('input[name="date_' + name + '"]').val(this.value);
    });

    $('.date select').on('change', function() {
        var name = $(this).attr('id');
        $('input[name="date_' + name + '"]').val(this.value);
    });

    function dropPin() {
        // if any previous marker exists, let's first remove it from the map
        if (endMarker) {
            endMarker.setMap(null);
        }
        // create the marker
        endMarker = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            draggable: true,
        });
        copyMarkerpositionToInput();
        // add an event "onDrag"
        google.maps.event.addListener(endMarker, 'dragend', function() {
            copyMarkerpositionToInput();
        });
    }

    function copyMarkerpositionToInput() {
        $('input[name="location_lat"]').val(endMarker.getPosition().lat());
        $('input[name="location_lng"]').val(endMarker.getPosition().lng());
    }

    var $form = $('#timelineEventCreate form');

    $form.on('submit', function(e) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(response) {
            $.modal.close();
            loadEvents();
            $.ajax({
                type: 'PUT',
                url: '/timelines/' + response.timeline_id + '/reorder',
                dataType: 'json',
                encode: true
            });
            //console.log(response.result);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            var errorData = JSON.parse(jqXHR.responseText);
            mapErrorsToForm(errorData.errors);
            //console.log(errorData);
            //console.log(textStatus);
            //console.log(errorThrown);
        }).always(function() {
            // Always run after .done() or .fail()
        });
        e.preventDefault();
    });

    function mapErrorsToForm(errorData) {
        // reset things!
        $form.find('.control__error').remove();
        $form.find(':input').each(function() {
            var fieldName = $(this).attr('name');
            if (!errorData[fieldName]) {
                // no error!
                return;
            }
            var $error = $('<span class="control__error"></span>');
            $error.html(errorData[fieldName]);
            $(this).after($error);
        });
    }

}






/*let map;

function initMap() {
    map = new google.maps.Map(document.getElementById("gmap"), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8,
        mapTypeId: "roadmap",
        disableDefaultUI: true,
        options: {
            gestureHandling: 'greedy'
        }
    });
}

window.initMap = initMap;*/