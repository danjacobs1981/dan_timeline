import ScrollMagic from 'scrollmagic';

window.loadEvents = function(share, tags) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/ajax/timeline/events/' + $('meta[name="timeline"]').attr('content'),
        data: { 'share': share, 'tags': tags },
        type: 'POST',
        dataType: 'json',
        success: function(result) {

            $('.events-wrapper').html(result['events_html']).promise().done(function() {


                //$('.events').css('height', 'calc((100vh - 208px) + ' + $('.events').height() + 'px)');
                $('.timeline--map .timeline__body').css('height', 'auto');

                scrollEvents();
                setMap();

            });

            if (result['events_count'] === 1) {
                $('.filter__show').text('Show 1 result');
            } else {
                $('.filter__show').text('Show ' + result['events_count'] + ' results');
            }

        }
    });
}

loadEvents(null, false);

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
        loadEvents(urlParams.get('share'), false);
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
});

function scrollEvents() {
    $('.events-wrapper .event-title').each(function() {
        new ScrollMagic.Scene({
                offset: -headerHeight - 80,
                triggerElement: $(this)[0],
            })
            .triggerHook(0)
            .on('enter', function(e) { // forward
                var $element = $(e.target.triggerElement());
                $('.events-time span').text($element.data('period'));
                $('.event-title').removeClass('active');
                $element.addClass('active');
                //$('.events-wrapper section').removeClass('active');
                //$element.parent().addClass('active');
                if ($element.hasClass('event-last')) {
                    $('i.events-down').css('color', '#9b9b9b');
                }
                $('i.events-up').css('color', '#ffffff');
            })
            .on('leave', function(e) { // reverse
                var $element = $(e.target.triggerElement());
                var order = $element.data('order') - 1;
                //$('.events-wrapper .event-item').removeClass('event-current');
                $('.event-title').removeClass('active');
                if ($('.event-item[data-order="' + order + '"]').length) {
                    $('.event-title[data-order="' + order + '"]').addClass('active');
                    $('.events-time span').text($('.event-title[data-order="' + order + '"]').data('period'));
                    $('i.events-down').css('color', '#ffffff');
                } else {
                    $('.events-time span').html('Start of timeline');
                    $('i.events-up').css('color', '#9b9b9b');
                }
            })
            .addTo(controller);
    });
}

/*function scrollEvents() {
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
}*/


$('i.events-increment').on('click', function() {
    var scrollTop = 0;
    if ($(this).hasClass('events-up') || ($(this).hasClass('events-down') && !$('.event-title').hasClass('event-last'))) {
        var increment = -1;
        if ($(this).hasClass('events-down')) {
            increment = 1;
        }
        var order = 0;
        if ($('.event-title.active').length) {
            order = $('.event-title.active').data('order');
        }
        var $goElement = $('.event-title[data-order="' + (order + increment) + '"]');
        if ($goElement.length) {
            scrollTop = $goElement.offset().top - headerHeight - 79;
        } else {
            if ($(this).hasClass('events-down')) {
                //scrollTop = $('.event-first').offset().top - headerHeight;
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
    loadEvents(null, true);
});

/* events */
$('.events-wrapper').on('click', '.event-close, .event-read, .event-source, .event-subheader > li', function() {
    var $event = $(this).closest('.event');
    if ($event.hasClass('event--open')) {
        $event.removeClass('event--open');
    } else {
        $event.addClass('event--open');
    }
});