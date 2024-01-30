import $ from 'jquery';
import { classEvents } from './../timeline/scripts';
// import ScrollMagic from 'scrollmagic';
import * as resizable from 'jquery-resizable-dom';
import { Loader } from '@googlemaps/js-api-loader';

let map;
var mapInit = 0;
var mapLoaded = 0;
var mapFirstRun = 0;
let markers = [];

function startMap() {

    mapInit = 1;

    const loader = new Loader({
        apiKey: import.meta.env.VITE_GOOGLE_API,
        version: "weekly"
    });

    const mapOptions = {
        center: { lat: 0, lng: 0 },
        zoom: 3,
        maxZoom: 19,
        minZoom: 1,
        mapId: "53cedd9afde08104",
        //mapTypeId: "hybrid",
        disableDefaultUI: true,
        options: {
            gestureHandling: 'greedy'
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

            map.addListener('zoom_changed', () => {
                //console.log('zoom');
            });

            $('select[name="location_zoom"]').on('change', function() {
                map.setZoom(parseInt($(this).val()));
            });

            mapLoaded = 1;

            console.log('map loaded');

        })
        .catch((e) => {
            // do something
        });

    $('.map-in').on('click', function() {
        map.setZoom(map.getZoom() + 1);
    });

    $('.map-out').on('click', function() {
        map.setZoom(map.getZoom() - 1);
    });

    $('.map-fullscreen').on('click', function() {
        $('#map').toggleClass('fullscreen');
    });

}

export function start() {

    $(window).on('resize', function() {
        if (!mapInit && screenSize > 2) {
            startMap();
        }
    });

    if (!mapInit && screenSize > 2) {
        startMap();
    }

    $('.map-layer').on('click', function() {
        if (mapLoaded) {
            var mapType = $(this).data('type');
            if (mapType == 'terrain') {
                map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
            } else if (mapType == 'satellite') {
                map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
            } else if (mapType == 'hybrid') {
                map.setMapTypeId(google.maps.MapTypeId.HYBRID);
            } else {
                map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
            }
        }
    });

    $('.events').on('click', '.event-map, .event-location', function() {
        var $el = $(this).closest('div.event-item').find('.event-location');
        if (screenSize <= 2) {
            $('.map-open').trigger('click');
        }
        if (!mapInit) {
            startMap();
        }
        if (mapLoaded) {
            targetMap($el);
        } else {
            let interval = setInterval(function() {
                if (mapLoaded) {
                    clearInterval(interval);
                    targetMap($el);
                }
            }, 1000);
        }
    });

    $('article').resizable({
        handleSelector: '.splitter',
        resizeHeight: false,
        onDragEnd: function() { // changed from onDrag to onDragEnd for performance
            /* give article a class dependent of width */
            classEvents();
        }
    });

    $('.map-expand').on('click', function() {
        $('figure').addClass('fullscreen');
    });

    $('.map-compress').on('click', function() {
        $('figure').removeClass('fullscreen');
    });

    $('.map-close').on('click', function() {
        $('figure').css('transform', 'translateY(' + ($("figure").height() + 46) + 'px)');
        $('article').css('padding-bottom', '76px');
        $('.map-open').css('transform', 'translate(-50%, 0)');
    });

    $('.map-open').on('click', function() {
        if (!mapInit) {
            startMap();
        }
        $('figure').css('transform', 'translateY(0)');
        $("article").css('padding-bottom', ($("figure").height() + 46) + 'px');
        $(this).css('transform', 'translate(-50%, 100px)');
    });

    $("figure").resizable({
        handleSelector: ".resizer > span",
        resizeWidth: false,
        resizeHeightFrom: 'top',
        onDragEnd: function() {
            /* give padding to bottom of events list (+ resizer height + original bottom padding) */
            $("article").css('padding-bottom', ($("figure").height() + 46) + 'px');
        }
    });

}

export function targetMap($el) {
    var lat = $el.data('lat');
    var lng = $el.data('lng');
    var zoom = $el.data('zoom');
    setMapPosition(lat, lng, zoom);
}

function setMapOnAll(map) {
    for (let i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

function setMapPosition(lat, lng, zoom) {
    map.panTo(new google.maps.LatLng({ lat: parseFloat(lat), lng: parseFloat(lng) }));
    map.setZoom(parseInt(zoom));
}

export function loadMarkers(markersArray) {
    let interval = setInterval(function() {
        if (mapLoaded) {
            clearInterval(interval);

            setMapOnAll(null);
            markers = [];

            var bounds = new google.maps.LatLngBounds();

            const label = {
                fontFamily: "'Font Awesome 5 Free'",
                fontWeight: '900',
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

            $.each(JSON.parse(markersArray), function(index, item) {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(item.location_lat), lng: parseFloat(item.location_lng) },
                    map: map,
                    label: label,
                    icon: icon
                });
                markers.push(marker);
                bounds.extend(marker.getPosition());
            });

            if (!mapFirstRun) {
                map.fitBounds(bounds);
                map.setZoom(map.getZoom() - 1);
                $('#map>figure>.loading').fadeOut();
            }
            mapFirstRun = 1;

        }
    }, 1000);
}