import ScrollMagic from 'scrollmagic';



// create a scene
/*var scene = new ScrollMagic.Scene({ triggerElement: "header", triggerHook: 0 })
    .setPin("header")
    .addTo(controller);

var scene = new ScrollMagic.Scene({
    triggerElement: 'section>h3',
    triggerHook: 0
}).setClassToggle('div.events', 'test' + Math.random()).addTo(controller);*/


/*$('.events section').each(function() {
    var offset = -56; // header1
    if ($(this).hasClass('month')) {
        offset = -76;
    } else if ($(this).hasClass('day')) {
        offset = -96;
    } else if ($(this).hasClass('time')) {
        offset = -116;
    }
    new ScrollMagic.Scene({
            offset: -74,
            triggerElement: $(this)[0],
            triggerHook: 0, // on enter
            duration: $(this).height() //  height of section
        })
        .setClassToggle('.events', $(this).data('period'))
        .addTo(controller);

    new ScrollMagic.Scene({
            offset: -74,
            triggerElement: $(this)[0],
            triggerHook: 0, // on enter
            duration: $(this).height() //  height of section
        })
        .setClassToggle($(this)[0], 'current')
        .addTo(controller);
});*/

$('.events-wrapper h3').each(function() {
    var offset = -80;
    if (isMobile) {
        offset = -92;
    }
    new ScrollMagic.Scene({
            offset: offset,
            triggerElement: $(this)[0],
        })
        .triggerHook(0)
        .on('enter', function(e) { // forward
            var $element = $(e.target.triggerElement());
            $('.events-time span').text($element.data('time'));
            $('.events-wrapper h3').removeClass('event-current');
            $element.addClass('event-current');
            //location.hash = '#' + $(e.target.triggerElement()).attr('id');
        })
        .on('leave', function(e) { // reverse
            var $element = $(e.target.triggerElement());
            if (prevEventDate($element, '.event-item', false) > 0) {
                $('.events-time span').text(prevEventDate($element, '.event-item', true));
            } else {
                $('.events-time span').text(prevEventDate($element, 'section', true));
            }
            $('.events-wrapper h3').removeClass('event-current');
            $element.addClass('event-current');
            //location.hash = '#' + $(e.target.triggerElement()).attr('id');
        })

    .addTo(controller);
});

function prevEventDate($element, area, time) {
    if (time) {
        return $element.closest(area).prev().find('h3').data('time');
    }
    return $element.closest(area).prev().find('h3').length;
}

function nextEvent($element, area) {
    return $element.closest(area).next().find('h3').length;
}



$('#down').on('click', function() {
    var $currentElement = $('.event-current');
    var $nextElement = $currentElement.next('.event');
    // Check if next element actually exists
    if ($nextElement.length) {
        // If yes, update:
        // 1. $currentElement
        // 2. Scroll position
        $currentElement = $nextElement;
        $('html, body').stop(true).animate({
            scrollTop: $nextElement.offset().top
        }, 500);
    }
    return false;
});

/* header buttons */
$('.header__options-share').on('click', function() {
    $('#modal-share').modal();
});

/* header dropdown (tag) filter */
$('.dropdown-toggle input[type="checkbox"]').on('change', function() {
    var $dropdown = $(this).closest('.dropdown-toggle');
    var dd_checked = $dropdown.find('input:checkbox:checked').length;
    if (dd_checked > 0) {
        $dropdown.find('em.count').remove();
        $dropdown.find('i.fa-chevron-down').before('<em class="count">' + dd_checked + '</em>');
    } else {
        $dropdown.find('em.count').remove();
    }
});