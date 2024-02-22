import $ from 'jquery';
import ScrollMagic from 'scrollmagic';
import { screenSize, urlParams } from './../global';
import { loadMarkers, mapLoaded, mapSync, getMapClick, setMapClick, targetMap, markers } from './../timeline/map';

export var timeline_id = parseInt($('meta[name="timeline"]').attr('content'));

var topHeight = getTopHeight();

export function start() {

    setLayout();
    classEvents();
    //loadEvents(null, []);

    $(window).on('resize', function() {
        topHeight = getTopHeight();
        setLayout();
        setEventElements();
        classEvents();
    });

    $(window).on('load', function() {
        setTimeout(scrollOnPageLoad(), 500);
    });

    /* timeline scroll increment up/down */
    $('i.events-increment').on('click', function() {
        if ($(this).hasClass('events-up') || ($(this).hasClass('events-down') && !$('.event-item').hasClass('event-last'))) {
            var increment = -1;
            if ($(this).hasClass('events-down')) {
                increment = 1;
            }
            var order = -1;
            if ($('.event-item.active').length) {
                order = $('.event-item.active').data('order');
            }
            var $targetEl = $('.event-item[data-order="' + (order + increment) + '"]');
            timelineScrollTo($targetEl);
        }
        //return false;
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
    $('.events-wrapper').on('click', '.event h3, .event-close, .event-read, .event-source, .event:not(.event--open) img', function() {
        var $event = $(this).closest('.event');
        if ($event.hasClass('event--open')) {
            $event.removeClass('event--open');
        } else {
            $event.addClass('event--open');
        }
    });

    $('.events-wrapper').on('click', '.event--open img', function() {
        console.log('view img');
    });

    $('.events').on('click', '[data-reveal="comments"]', function() {
        var event_id = $(this).closest('.event-item').data('id');
        $('.comments-add textarea').focus();
        loadComments(event_id);
    });

    $('li.header__options-comments').on('click', function() {
        $('.comments-add textarea').focus();
        loadComments(null);
    });

    /* like & save */
    $('li.header__options-like, li.header__options-save').on('click', function() {
        var $el = $(this);
        $el.addClass('loading');
        var type = 'like';
        if ($el.hasClass('header__options-save')) {
            type = 'save';
        }
        $.ajax({
            type: 'POST',
            url: '/timeline/' + timeline_id + '/' + type,
            dataType: 'json',
        }).done(function(response) {
            window.setTimeout(function() {
                $el.removeClass('loading');
                if (response.success) {
                    if (response.increment) {
                        if (type == 'like') {
                            $el.addClass('color-liked').removeClass('color-like').find('span').text(response.count);
                        } else {
                            $el.addClass('color-saved').removeClass('color-save').find('span').text('Saved');
                        }
                    } else {
                        if (type == 'like') {
                            $el.addClass('color-like').removeClass('color-liked').find('span').text(response.count);
                        } else {
                            $el.addClass('color-save').removeClass('color-saved').find('span').text('Save');
                        }
                    }
                } else {
                    $('#modal-signup').modal({
                        modalClass: 'modal-login modal-md'
                    });
                }
            }, 200);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }).always(function() {
            // Always run after .done() or .fail()
        });
    });

}

export function timelineScrollTo($targetEl) {
    var scrollTop = 0;
    if ($targetEl.length) {
        var diff = 13;
        if (screenSize > 2) {
            diff = 64;
        }
        scrollTop = $targetEl[0].offsetTop - diff;
    }
    var el = 'html';
    if (screenSize > 2) {
        el = 'article';
    }
    $(el).stop(true).animate({
        scrollTop: scrollTop
    }, 500);
    $targetEl.addClass('highlight');
    setTimeout(function() {
        $targetEl.removeClass('highlight');
    }, 3000);
}

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
        $('.timeline--map #map>figure').css({
            'max-height': 'calc(100vh - ' + (topHeight + 154) + 'px)'
        });
    }
}

export function classEvents() {
    $('.events').removeClass('events--sm events--md events--lg');
    if ($('.events').width() > 499) {
        $('.events').addClass('events--sm');
    }
    if ($('.events').width() > 579) {
        $('.events').addClass('events--md');
    }
}

var xhr_events; // to abort requests

export function loadEvents(share, tags) {
    if (xhr_events && xhr_events.readyState != 4) {
        xhr_events.abort();
    }
    $('.events .events-wrapper').html();
    $('.events .loading').show();
    var data = { share: share, tags: tags };
    xhr_events = $.ajax({
        type: 'GET',
        url: '/timeline/' + timeline_id + '/events',
        data: data,
        dataType: 'json',
        encode: true,
    }).done(function(response) {
        $('#filters .filter__show').removeClass('loading');
        loadMarkers(response.events_markers);
        console.log(response.events_markers);
        $('.events-wrapper').html(response.events_html).promise().done(function() {
            scrollEvents();
            setEventElements();
            $('.events .loading').fadeOut();
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

export function loadComments(event) {
    $('.comments .comments-wrapper').html('');
    $('.comments .loading').show();
    var url = '/timeline/' + timeline_id + '/comments';
    if (event) {
        url = '/timeline/' + timeline_id + '/comments/' + event;
    }
    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        encode: true,
    }).done(function(response) {
        $('.comments-wrapper').html(response.comments_html).promise().done(function() {
            $('.comments .loading').fadeOut();
        });
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

/* timeline scroll indicator */
function scrollEvents() {
    var controller = new ScrollMagic.Controller();
    $('.events-wrapper .event-item').each(function() {
        new ScrollMagic.Scene({
                offset: (-topHeight) - 140,
                triggerElement: $(this)[0],
            })
            .triggerHook(0)
            .on('enter', function(e) { // forward

                var $targetEl = $(e.target.triggerElement());
                updateTimeline('forward', $targetEl);

            })
            .on('leave', function(e) { // reverse

                var $targetEl = $('.event-item[data-order="' + ($(e.target.triggerElement()).data('order') - 1) + '"]'); // prev event
                updateTimeline('reverse', $targetEl);

            })
            .addTo(controller);
    });
}

var timer;

function updateTimeline(direction, $targetEl) {

    $('.event-item').removeClass('active');
    $targetEl.addClass('active');

    clearTimeout(timer);

    if (mapLoaded && mapSync && $targetEl.find('.event-location').length) {
        if (!getMapClick()) {
            timer = setTimeout(function() {
                var zoom = $targetEl.find('.event-location').data('zoom');
                var marker = $targetEl.find('.event-location').data('marker');
                var latlng = markers[marker].getPosition();
                targetMap(latlng, zoom, marker);
            }, 1000);
        } else {
            setMapClick(0);
        }

    }

    var $element_title = $targetEl.closest('section').find('.event-title');

    if (!$element_title.length) {
        $element_title = $targetEl.closest('section.event-group').find('.event-title');
    }

    if ($element_title.length) {
        var period = "period";
        if (screenSize <= 2 && (typeof $element_title.attr('data-periodshort') !== typeof undefined && $element_title.attr('data-periodshort') !== false)) {
            period = "periodshort";
        }
        $('.events-time span').text($element_title.data(period));
    } else {
        if (direction == 'reverse') {
            $('.events-time span').html('Timeline Start');
            $('i.events-up').css('color', 'var(--grey)');
        }
    }
    if (direction == 'forward') {
        $('i.events-up').css('color', 'var(--greyDark)');
    } else {
        $('i.events-down').css('color', 'var(--greyDark)');
    }

}