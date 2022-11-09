import * as resizable from 'jquery-resizable-dom'

$(window).on('resize', function() {
    eventsWide();
});

eventsWide();

/* map */
let map;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8,
        mapTypeId: "roadmap",
        disableDefaultUI: true, //isMobile
        options: {
            gestureHandling: 'greedy'
        }
    });

}
window.initMap = initMap;

function eventsWide() {
    if ($('.events').width() > 429) {
        $('.events').addClass('events--wide');
    } else {
        $('.events').removeClass('events--wide');
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
        eventsWide();
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