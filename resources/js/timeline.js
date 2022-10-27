import ScrollMagic from 'scrollmagic';

var offset = timelineOffset();

$(window).on('resize', function() {
    offset = timelineOffset();
});

function timelineOffset() {
    return $('header').outerHeight() + 24;
}

function scrollOnPageLoad() {
    if (window.location.hash) scroll(0, 0);
    setTimeout(scroll(0, 0), 1);
    var hashLink = window.location.hash;
    if ($(hashLink).length) {
        $(function() {
            $('section.timeline').removeClass('timeline--header');
            // should position map too
            // maybe create specific function for ?event=32
            $('html, body').animate({
                scrollTop: $(window.location.hash).offset().top - offset
            }, 1000);
        });
    }
}

$(window).on('load', function() {
    setTimeout(scrollOnPageLoad(), 500);
});

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

$('.events-wrapper .event-item').each(function() {
    var scene = new ScrollMagic.Scene({
            offset: -offset - 1,
            triggerElement: $(this)[0],
        })
        .triggerHook(0)
        .on('enter', function(e) { // forward
            var $element = $(e.target.triggerElement());
            $('.events-wrapper .event-item').removeClass('event-current');
            $('.events-time span').html($element.find('h3').html());
            $element.addClass('event-current');
            if ($element.hasClass('event-last')) {
                $('i.events-down').css('color', '#9b9b9b');
            }
            $('i.events-up').css('color', '#ffffff');
        })
        .on('leave', function(e) { // reverse
            var $element = $(e.target.triggerElement());
            var order = $element.data('order') - 1;
            $('.events-wrapper .event-item').removeClass('event-current');
            if ($('.event-item[data-order="' + order + '"]').length) {
                $('.event-item[data-order="' + order + '"]').addClass('event-current');
                $('.events-time span').html($('.event-item[data-order="' + order + '"] h3').html());
                $('i.events-down').css('color', '#ffffff');
            } else {
                $('.events-time span').html('Start of timeline');
                $('i.events-up').css('color', '#9b9b9b');
            }
            //location.hash = '#' + $(e.target.triggerElement()).attr('id');
        })
        .addTo(controller);
});



$('i.events-increment').on('click', function() {
    var scrollTop = 0;
    if ($(this).hasClass('events-up') || ($(this).hasClass('events-down') && !$('.event-current').hasClass('event-last'))) {
        var increment = -1;
        if ($(this).hasClass('events-down')) {
            increment = 1;
        }
        var order = $('.event-current').data('order');
        var $goElement = $('.event-item[data-order="' + (order + increment) + '"]');
        if ($goElement.length) {
            scrollTop = $goElement.offset().top - offset;
        } else {
            if ($(this).hasClass('events-down')) {
                scrollTop = $('.event-first').offset().top - offset;
            }
        }
        $('html, body').stop(true).animate({
            scrollTop: scrollTop
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