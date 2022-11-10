import ScrollMagic from 'scrollmagic';
import * as resizable from 'jquery-resizable-dom';

eventsClass();

$(window).on('resize', function() {
    eventsClass();
});

/* map */
let map;

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
window.initMap = initMap;

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

function eventsClass() {
    $('.events').removeClass('events--sm events--md events--lg');
    if ($('.events').width() > 499) {
        $('.events').addClass('events--sm');
    }
    if ($('.events').width() > 579) {
        $('.events').addClass('events--md');
    }
}

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

$('article').resizable({
    handleSelector: '.splitter',
    resizeHeight: false,
    onDrag: function() {
        /* give article a class dependent of width */
        eventsClass();
    }
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