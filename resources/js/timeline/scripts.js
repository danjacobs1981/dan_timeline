import ScrollMagic from 'scrollmagic';

window.loadEvents = function(share, tags) {
    $.ajax({
        url: '/timelines/' + $('meta[name="timeline"]').attr('content') + '/events',
        data: { 'share': share, 'tags': tags },
        type: 'POST',
        dataType: 'json',
        success: function(result) {

            $('.events-wrapper').html(result['events_html']).promise().done(function() {


                //$('.events').css('height', 'calc((100vh - 208px) + ' + $('.events').height() + 'px)');
                /*$('.timeline--map .timeline__body').css('height', 'auto');*/

                scrollEvents();
                /*setMap();*/
                setEventElements();

            });

            if (result['events_count'] === 1) {
                $('.filter__show').text('Show 1 result');
            } else {
                $('.filter__show').text('Show ' + result['events_count'] + ' results');
            }

        }
    });
}

var topHeight = getTopHeight();
setLayout();
loadEvents(null, false);

$(window).on('resize', function() {
    topHeight = getTopHeight();
    setLayout();
    setEventElements();
});

$(window).on('load', function() {
    setTimeout(scrollOnPageLoad(), 500);
});

function getTopHeight() {
    if (screenSize > 2) {
        return $('#topbar').outerHeight() + $('header').outerHeight();
    } else {
        return $('header').outerHeight();
    }
}

function setLayout() {
    if (screenSize > 2) {
        $('.timeline__body').css({
            'height': 'calc(100vh - ' + topHeight + 'px)'
        });
    } else {
        $('.timeline__body').css({
            'height': 'auto'
        });
    }
}

function setEventElements() {
    if (screenSize > 2) {
        $('.events-time').css({
            'top': 0
        });
        $('.event-close').css({
            'top': 72
        });
    } else {
        $('.events-time').css({
            'top': topHeight + 'px'
        });
        $('.event-close').css({
            'top': (topHeight + 72) + 'px'
        });
    }
}

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
                    scrollTop: $('.event-start').offset().top - topHeight
                }, 1000);
            });
        }
        window.history.replaceState(null, null, window.location.pathname);
    }
}

/* timeline scroll indicator */
function scrollEvents() {
    $('.events-wrapper .event-title').each(function() {
        new ScrollMagic.Scene({
                offset: (-topHeight) - 86,
                triggerElement: $(this)[0],
            })
            .triggerHook(0)
            .on('enter', function(e) { // forward
                var $element = $(e.target.triggerElement());
                var period = "period";
                if (screenSize <= 2 && $element.attr('data-periodshort') !== "") {
                    period = "periodshort";
                }
                $('.events-time span').text($element.data(period));
                $('.event-title').removeClass('active');
                $element.addClass('active');
                if ($element.hasClass('event-last')) {
                    $('i.events-down').css('color', '#9b9b9b');
                }
                $('i.events-up').css('color', '#ffffff');
            })
            .on('leave', function(e) { // reverse
                var $element = $(e.target.triggerElement());
                var order = $element.data('order') - 1;
                $('.event-title').removeClass('active');
                if ($('.event-item[data-order="' + order + '"]').length) {
                    $('.event-title[data-order="' + order + '"]').addClass('active');
                    var period = "period";
                    if (screenSize <= 2 && $element.attr('data-periodshort') !== "") {
                        period = "periodshort";
                    }
                    $('.events-time span').text($('.event-title[data-order="' + order + '"]').data(period));
                    $('i.events-down').css('color', '#ffffff');
                } else {
                    $('.events-time span').html('Start of timeline');
                    $('i.events-up').css('color', '#9b9b9b');
                }
            })
            .addTo(controller);
    });
}

/* timeline scroll increment up/down */
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
            var diff = topHeight + 85;
            if (screenSize > 2) {
                diff = 85;
            }
            scrollTop = $goElement[0].offsetTop - diff;
        } else {
            if ($(this).hasClass('events-down')) {
                //scrollTop = $('.event-first').offset().top - topHeight;
            }
        }
        var el = 'html, body';
        if (screenSize > 2) {
            el = 'article';
        }
        $(el).stop(true).animate({
            scrollTop: scrollTop
        }, 500);
    }
    return false;
});

/* header buttons */
$('.header__options-share').on('click', function() {
    $('#modal-share').modal({
        modalClass: 'modal-share'
    });
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