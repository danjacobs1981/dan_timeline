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

            console.log('map loaded ' + map.getZoom());

        })
        .catch((e) => {
            // do something
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

    $('.events').on('click', '.event-map, .event-location', function() {
        var $el = $(this).closest('div.event-item').find('.event-location');
        if (screenSize <= 2) {
            $('.header__options-map').trigger('click');
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

    // desktop controls

    $('.map-in').on('click', function() {
        map.setZoom(map.getZoom() + 1);
    });

    $('.map-out').on('click', function() {
        map.setZoom(map.getZoom() - 1);
    });

    $('.map-fullscreen').on('click', function() {
        $('#map').toggleClass('fullscreen');
    });

    $('.map-map').on('click', function() {
        map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
    });

    $('.map-satellite').on('click', function() {
        map.setMapTypeId(google.maps.MapTypeId.HYBRID);
    });


    // mobile controls 

    $('.map-expand').on('click', function() {
        $('figure').addClass('fullscreen');
    });

    $('.map-compress').on('click', function() {
        $('figure').removeClass('fullscreen');
    });

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

    $('.map-close').on('click', function() {
        $('figure').css('transform', 'translateY(' + ($("figure").height() + 46) + 'px)');
        $('article').css('padding-bottom', '76px');
        $('.header__options-map').css('transform', 'translate(-50%, 0)');
    });

    $('.header__options-map').on('click', function() {
        if (!$('.timeline').hasClass('timeline--mapopen')) {
            if (!mapInit) {
                startMap();
            }
            $('.timeline').addClass('timeline--mapopen');
            $('figure').css('transform', 'translateY(0)');
            $("article").css('padding-bottom', ($("figure").height() + 96) + 'px');
        } else {
            $('.timeline').removeClass('timeline--mapopen');
            $('figure').css('transform', 'translateY(' + ($("figure").height() + 46) + 'px)');
        }

    });

    $("figure").resizable({
        handleSelector: '.resizer',
        resizeWidth: false,
        resizeHeightFrom: 'top',
        onDragEnd: function() {
            /* give padding to bottom of events list (+ resizer height + original bottom padding) */
            $("article").css('padding-bottom', ($("figure").height() + 96) + 'px');
        }
    });

}

export function panMap($el) {
    var lat = $el.data('lat');
    var lng = $el.data('lng');
    smoothlyAnimatePanTo(map, new google.maps.LatLng({ lat: parseFloat(lat), lng: parseFloat(lng) }), 8);
}

export function targetMap($el) {
    var lat = $el.data('lat');
    var lng = $el.data('lng');
    var zoom = $el.data('zoom');

    smoothlyAnimatePanTo(map, new google.maps.LatLng({ lat: parseFloat(lat), lng: parseFloat(lng) }), zoom);

    //setMapPosition(lat, lng, zoom);
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


function project(latLng) {
    var TILE_SIZE = 256
    var siny = Math.sin(latLng.lat() * Math.PI / 180)
    siny = Math.min(Math.max(siny, -0.9999), 0.9999)
    return new google.maps.Point(
        TILE_SIZE * (0.5 + latLng.lng() / 360),
        TILE_SIZE * (0.5 - Math.log((1 + siny) / (1 - siny)) / (4 * Math.PI)))
}

function getPixel(latLng, zoom) {
    var scale = 1 << zoom
    var worldCoordinate = project(latLng)
    return new google.maps.Point(
        Math.floor(worldCoordinate.x * scale),
        Math.floor(worldCoordinate.y * scale))
}

function getMapDimenInPixels(map) {
    var zoom = map.getZoom()
    var bounds = map.getBounds()
    var southWestPixel = getPixel(bounds.getSouthWest(), zoom)
    var northEastPixel = getPixel(bounds.getNorthEast(), zoom)
    return {
        width: Math.abs(southWestPixel.x - northEastPixel.x),
        height: Math.abs(southWestPixel.y - northEastPixel.y)
    }
}

function willAnimatePanTo(map, destLatLng, optionalZoomLevel) {
    var dimen = getMapDimenInPixels(map)

    var mapCenter = map.getCenter()
    optionalZoomLevel = !!optionalZoomLevel ? optionalZoomLevel : map.getZoom()

    var destPixel = getPixel(destLatLng, optionalZoomLevel)
    var mapPixel = getPixel(mapCenter, optionalZoomLevel)
    var diffX = Math.abs(destPixel.x - mapPixel.x)
    var diffY = Math.abs(destPixel.y - mapPixel.y)

    return diffX < dimen.width && diffY < dimen.height
}

function getOptimalZoomOut(latLng, currentZoom) {
    if (willAnimatePanTo(map, latLng, currentZoom - 1)) {
        return currentZoom - 1
    } else if (willAnimatePanTo(map, latLng, currentZoom - 2)) {
        return currentZoom - 2
    } else {
        return currentZoom - 3
    }
}

function smoothlyAnimatePanToWorkaround(map, destLatLng, optionalAnimationEndCallback) {
    var initialZoom = map.getZoom(),
        listener

    function zoomIn() {
        if (map.getZoom() < initialZoom) {
            map.setZoom(Math.min(map.getZoom() + 3, initialZoom))
        } else {
            google.maps.event.removeListener(listener)

            //here you should (re?)enable only the ui controls that make sense to your app 
            map.setOptions({ draggable: true, zoomControl: false, scrollwheel: true, disableDoubleClickZoom: false })

            /*if (!!optionalAnimationEndCallback) {
                optionalAnimationEndCallback()
            }*/
        }
    }

    function zoomOut() {
        if (willAnimatePanTo(map, destLatLng)) {
            google.maps.event.removeListener(listener)
            listener = google.maps.event.addListener(map, 'idle', zoomIn)
            map.panTo(destLatLng)
        } else {
            map.setZoom(getOptimalZoomOut(destLatLng, map.getZoom()))
        }
    }

    //here you should disable all the ui controls that your app uses
    map.setOptions({ draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: true })
    map.setZoom(getOptimalZoomOut(destLatLng, initialZoom))
    listener = google.maps.event.addListener(map, 'idle', zoomOut)
}

function smoothlyAnimatePanTo(map, destLatLng, zoom) {

    console.log(map.getZoom() + ' complex pan ' + zoom);
    smoothlyAnimatePanToWorkaround(map, destLatLng, zoom)

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