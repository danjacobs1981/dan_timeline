import $ from 'jquery';
import { classEvents, timelineScrollTo } from './../timeline/scripts';
// import ScrollMagic from 'scrollmagic';
import * as resizable from 'jquery-resizable-dom';
import { Loader } from '@googlemaps/js-api-loader';
import { screenSize } from './../global';

let map;
let mapInit = 0;
export let mapLoaded = 0;
let mapFirstRun = 0;
let infoWindow;
export let markers = [];
export let mapSync = 1;

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
            gestureHandling: 'greedy',
            clickableIcons: false
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

            /*map.addListener('zoom_changed', () => {
                 //console.log('zoom');
             });*/

            map.addListener('click', function() {
                if (infoWindow) infoWindow.close();
            });

            /*$('select[name="location_zoom"]').on('change', function() {
                map.setZoom(parseInt($(this).val()));
            });*/

            mapLoaded = 1;

            //console.log('map loaded ' + map.getZoom());

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

    // event - view on map button

    $('.events').on('click', '.event-map, .event-location', function() {
        if (screenSize <= 2 && !$('.timeline').hasClass('timeline--mapopen')) {
            $('.header__options-map').trigger('click');
        }
        if (!mapInit) {
            startMap();
        }
        if (mapLoaded) {
            var $el = $(this).closest('.event-item').find('.event-location');
            var zoom = $el.data('zoom');
            var marker = $el.data('marker');
            var latlng = markers[marker].getPosition();
        }
        if (mapLoaded) {
            targetMap(latlng, zoom, marker);
        } else {
            let interval = setInterval(function() {
                if (mapLoaded) {
                    clearInterval(interval);
                    targetMap(latlng, zoom, marker);
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

    $('.map-more').on('click', function() {
        $('.timeline').toggleClass('timeline--mapmore');
    });

    $('.map-fullscreen').on('click', function() {
        $('.timeline').toggleClass('timeline--mapfullscreen').removeClass('timeline--mapmore');
    });

    $('.map-close').on('click', function() {
        $('.header__options-map').trigger('click');
        $('.timeline').removeClass('timeline--mapfullscreen');
    });

    $('.map-map').on('click', function() {
        map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
    });

    $('.map-satellite').on('click', function() {
        map.setMapTypeId(google.maps.MapTypeId.HYBRID);
    });

    $('input[type="checkbox"]#map_auto').on('change', function() {
        if (this.checked) {
            mapSync = 1;
        } else {
            mapSync = 0;
        }
    });

    // infowindow controls

    $('#gmap').on('click', '.infowindow>ul>li>a', function(e) {
        e.preventDefault();
        var action = $(this).closest('li').data('action');
        var marker = $(this).closest('.infowindow').data('marker');
        if (action == 'zoom') {
            $('.event-item').removeClass('highlight');
            map.setCenter(markers[marker].getPosition());
            if ($(this).data('zoom') != null) {
                map.setZoom(parseInt($(this).data('zoom')));
            } else {
                map.setZoom(19);
            }
            $('.event-item[data-marker="' + marker + '"]').addClass('highlight');
        } else if (action == 'details') {
            var $targetEl = $('.event-item[data-marker="' + marker + '"]');
            timelineScrollTo($targetEl);
        } else if (action == 'event') {
            if ($(this).data('direction') == 'previous') {
                marker = marker - 1;
            } else {
                marker = marker + 1;
            }
            var latlng = markers[marker].getPosition();
            var zoom = map.getZoom();
            targetMap(latlng, zoom, marker);
            if (mapSync) {
                var $targetEl = $('.event-item[data-marker="' + marker + '"]');
                timelineScrollTo($targetEl);
            }

        }
    });

    // mobile open map button

    $('.header__options-map').on('click', function() {
        if (!$('.timeline').hasClass('timeline--mapopen')) {
            if (!mapInit) {
                startMap();
            }
            $('.timeline').addClass('timeline--mapopen');
            $('figure').css('transform', 'translateY(0)');
            $("article").css('padding-bottom', ($("figure").height() + 96) + 'px');
            $(this).addClass('active');
        } else {
            $('.timeline').removeClass('timeline--mapopen');
            $('figure').css('transform', 'translateY(' + ($("figure").height() + 108) + 'px)'); //6.75rem
            $('article').css('padding-bottom', '4.5rem');
            $(this).removeClass('active');
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

export function panMap(test) { // unused right now
    //var lat = $el.data('lat');
    //var lng = $el.data('lng');
    smoothlyAnimatePanTo(map, new google.maps.LatLng(test), 8);
}

export function targetMap(latlng, zoom, marker) {

    infoWindow.close();

    smoothlyAnimatePanTo(map, new google.maps.LatLng(latlng), zoom);

    if (screenSize > 2 && marker != null) {
        google.maps.event.trigger(markers[marker], 'click');
    }

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

            infoWindow = new google.maps.InfoWindow();

            /*const label = {
                fontFamily: "'Font Awesome 5 Free'",
                fontWeight: '900',
                fontSize: '40px',
                color: 'red',
                text: '\uf3c5'
            };*/

            /*const icon = {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 20,
                anchor: new google.maps.Point(0, 1), // bottom of circle
                strokeWeight: 0
            };*/

            $.each(markersArray, function(index, item) {

                let icon = '/images/map/pin.png';
                let fa_icon = 'fa-map-pin';
                let extra_options = '';
                let prev = '';
                let next = '';

                if (index > 0) {
                    prev = '<a href="#" data-direction="previous"><i class="fa-solid fa-arrow-left"></i>Previous event</a>';
                }

                if (index != (markersArray.length - 1)) {
                    next = '<a href="#" data-direction="next">Next event<i class="fa-solid fa-arrow-right"></i></a>';
                }

                if (item.location_zoom > 16) {
                    icon = '/images/map/marker.png';
                    fa_icon = 'fa-map-marker-alt';
                    extra_options = '<li data-action="zoom"><a href="#"><i class="fa-solid fa-magnifying-glass-plus"></i>Zoom in</a></li><li data-action="street"><a href="#"><i class="fa-solid fa-street-view"></i>Street view</a></li>'
                } else {
                    extra_options = '<li data-action="zoom"><a href="#" data-zoom="' + item.location_zoom + '"><i class="fa-solid fa-magnifying-glass-plus"></i>Zoom to area</a></li>'
                }

                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(item.location_lat), lng: parseFloat(item.location_lng) },
                    map: map,
                    //label: label,
                    icon: icon
                });

                google.maps.event.addListener(marker, 'click', (function(marker, index) {
                    return function() {
                        infoWindow.setContent(
                            '<div class="infowindow" data-marker="' + index + '">' +
                            '<span><i class="fas ' + fa_icon + '"></i>' + item.location + '</span>' +
                            '<em><i class="fa-regular fa-calendar"></i>' + item.period + '</em>' +
                            '<strong>' + item.title + '</strong>' +
                            '<ul>' +
                            '<li data-action="details"><a href="#"><i class="fa-regular fa-note-sticky"></i>Event details</li>' +
                            extra_options +
                            '</ul>' +
                            '<ul class="infowindow-action"><li data-action="event">' + prev + '</li><li data-action="event">' + next + '</li></ul>' +
                            '</div>'
                        );
                        infoWindow.open(map, marker);
                    }
                })(marker, index));

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