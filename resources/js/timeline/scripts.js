import $ from 'jquery';
import ScrollMagic from 'scrollmagic';
//import { getScreenSize } from './../global.js';

window.classEvents = function() {
    $('.events').removeClass('events--sm events--md events--lg');
    if ($('.events').width() > 499) {
        $('.events').addClass('events--sm');
    }
    if ($('.events').width() > 579) {
        $('.events').addClass('events--md');
    }
}

var topHeight = getTopHeight();
setLayout();
classEvents();
loadEvents(null, false);

$(window).on('resize', function() {
    topHeight = getTopHeight();
    setLayout();
    setEventElements();
    classEvents();
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
        $('.timeline--map .timeline__body').css({
            'height': 'calc(100vh - ' + topHeight + 'px)'
        });
    } else {
        $('.timeline--map .timeline__body').css({
            'height': 'auto'
        });
    }
}

function loadEvents(share, tags) {
    $.ajax({
        type: 'GET',
        url: '/timeline/' + $('meta[name="timeline"]').attr('content') + '/events',
        dataType: 'json',
        encode: true,
    }).done(function(response) {
        $('.events-wrapper').html(response.events_html).promise().done(function() {
            //$('.events').css('height', 'calc((100vh - 208px) + ' + $('.events').height() + 'px)');
            /*$('.timeline--map .timeline__body').css('height', 'auto');*/
            scrollEvents();
            /*setMap();*/
            setEventElements();
        });
        if (response.events_count === 1) {
            $('.filter__show').text('Show 1 result');
        } else {
            $('.filter__show').text('Show ' + response.events_count + ' results');
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
    }).always(function() {
        // Always run after .done() or .fail()
    });
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

var controller = new ScrollMagic.Controller();

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
                if (screenSize <= 2 && (typeof $element.attr('data-periodshort') !== typeof undefined && $element.attr('data-periodshort') !== false)) {
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
                    if (screenSize <= 2 && (typeof $element.attr('data-periodshort') !== typeof undefined && $element.attr('data-periodshort') !== false)) {
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
$('.events-wrapper').on('click', '.event-close, .event-read, .event-source, .event-subheader > li, .event img', function() {
    var $event = $(this).closest('.event');
    if ($event.hasClass('event--open')) {
        $event.removeClass('event--open');
    } else {
        $event.addClass('event--open');
    }
});

/* like */
$('li.header__options-like, li.header__options-save').on('click', function() {
    var $el = $(this);
    $el.addClass('loading');
    var type = 'like';
    if ($el.hasClass('header__options-save')) {
        type = 'save';
    }
    $.ajax({
        type: 'POST',
        url: '/timeline/' + $('meta[name="timeline"]').attr('content') + '/' + type,
        dataType: 'json',
    }).done(function(response) {
        window.setTimeout(function() {
            $el.removeClass('loading');
            if (response.success) {
                if (response.increment) {
                    if (type == 'like') {
                        $el.addClass('colour-liked').removeClass('colour-like').find('span').html('Liked <em>' + response.count + '</em>');
                    } else {
                        $el.addClass('colour-saved').removeClass('colour-save').find('span').text('Saved');
                    }
                } else {
                    if (type == 'like') {
                        $el.addClass('colour-like').removeClass('colour-liked').find('span').html('Like <em>' + response.count + '</em>');
                    } else {
                        $el.addClass('colour-save').removeClass('colour-saved').find('span').text('Save');
                    }
                }
            } else {
                console.log("show modal");
            }
        }, 500);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
    }).always(function() {
        // Always run after .done() or .fail()
    });
});