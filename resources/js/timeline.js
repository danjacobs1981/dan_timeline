import ScrollMagic from 'scrollmagic';

$(window).on('resize', function() {
    $('.events-time').css('top', headerHeight + 'px');
    $('.event-close').css('top', (headerHeight + 73) + 'px');
});

$('.events-time').css('top', headerHeight + 'px');
$('.event-close').css('top', (headerHeight + 73) + 'px');

function scrollOnPageLoad() {
    //if (window.location.hash) scroll(0, 0);
    //setTimeout(scroll(0, 0), 1);
    //var hashLink = window.location.hash;
    if (urlParams.has('share')) {
        if ($('.event-start').length) {
            $(function() {
                $('section.timeline').removeClass('timeline--header');
                // open event details
                // position map using co-ordinates as data-attrs
                $('html, body').animate({
                    scrollTop: $('.event-start').offset().top - headerHeight
                }, 1000);
            });
        }
        window.history.replaceState(null, null, window.location.pathname);
    }
}

$(window).on('load', function() {
    setTimeout(scrollOnPageLoad(), 500);
    $('.events').css('height', 'calc((100vh - 208px) + ' + $('.events').height() + 'px)');
});

$('.events-wrapper .event-item').each(function() {
    new ScrollMagic.Scene({
            offset: -headerHeight - 1,
            triggerElement: $(this)[0],
        })
        .triggerHook(0)
        .on('enter', function(e) { // forward
            var $element = $(e.target.triggerElement());
            $('.events-wrapper .event-item').removeClass('event-current');
            $('.events-time span').html($element.find('h2').html());
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
                $('.events-time span').html($('.event-item[data-order="' + order + '"] h2').html());
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
            scrollTop = $goElement.offset().top - headerHeight;
        } else {
            if ($(this).hasClass('events-down')) {
                scrollTop = $('.event-first').offset().top - headerHeight;
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

/* events */
$('.event-close, .event-read, .event-source, .event-subheader > li').on('click', function() {
    var $event = $(this).closest('.event');
    if ($event.hasClass('event--open')) {
        $event.removeClass('event--open');
    } else {
        $event.addClass('event--open');
    }
});