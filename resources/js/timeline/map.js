import $ from 'jquery';
// import ScrollMagic from 'scrollmagic';
import * as resizable from 'jquery-resizable-dom';
import { Loader } from '@googlemaps/js-api-loader';

eventsClass();

$(window).on('resize', function() {
    eventsClass();
    /*setMap();*/
});

/* map */

let map;

const loader = new Loader({
    apiKey: import.meta.env.VITE_GOOGLE_API,
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

/* 
function initMap() {
    map = new google.maps.Map(document.getElementById("gmap"), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8,
        mapId: "53cedd9afde08104",
        mapTypeId: "roadmap",
        disableDefaultUI: true,
        options: {
            gestureHandling: 'greedy'
        }
    });

}
window.initMap = initMap;*/

/*window.setMap = function() {
    if (!isMobile) {
        new ScrollMagic.Scene({
                duration: topbarHeight
            })
            .triggerHook(0)
            .on('enter', function(e) { // forward 
                $('#map').css({
                    'top': topbarHeight + 'px',
                    'height': 'calc(100vh - (' + topbarHeight + 'px + ' + headerHeight + 'px))'
                });
            })
            .on('leave', function(e) { // reverse
                $('#map').css({
                    'top': headerHeight + 'px',
                    'height': 'calc(100vh - ' + headerHeight + 'px)'
                });
            })
            .addTo(controller);
    } else {
        $('#map').css({
            'height': 'auto'
        });
    }
}*/

/*window.setMap = function() {
    if (!isMobile) {
        $('#map').css({
            'top': topHeight + 'px',
            'height': 'calc(100vh - ' + topHeight + 'px)'
        });
    } else {
        $('#map').css({
            'height': 'auto'
        });
    }
}*/

function eventsClass() {
    $('.events').removeClass('events--sm events--md events--lg');
    if ($('.events').width() > 499) {
        $('.events').addClass('events--sm');
    }
    if ($('.events').width() > 579) {
        $('.events').addClass('events--md');
    }
}

$('article').resizable({
    handleSelector: '.splitter',
    resizeHeight: false,
    onDrag: function() {
        /* give article a class dependent of width */
        eventsClass();
    }
});

$('.map-expand').on('click', function() {
    $('figure').addClass('fullscreen');
});

$('.map-compress').on('click', function() {
    $('figure').removeClass('fullscreen');
});

$('.map-layer').on('click', function() {
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
});

$('.map-close').on('click', function() {
    $('figure').css('transform', 'translateY(' + ($("figure").height() + 46) + 'px)');
    $('article').css('padding-bottom', '0.5rem');
    $('.map-open').css('transform', 'translate(-50%, 0)');
});

$('.map-open').on('click', function() {
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