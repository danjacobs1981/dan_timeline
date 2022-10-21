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

$('.events-wrapper .event-item').each(function() {
    var offset = -88;
    if (isMobile) {
        offset = -99;
    }
    new ScrollMagic.Scene({
            offset: offset,
            triggerElement: $(this)[0],
        })
        .triggerHook(0)
        .on('enter', function(e) { // forward
            var $element = $(e.target.triggerElement());
            $('.events-time span').text($element.find('h3').data('time'));
            $('.events-wrapper .event-item').removeClass('event-current');
            $element.addClass('event-current');
            //location.hash = '#' + $(e.target.triggerElement()).attr('id');
        })
        .on('leave', function(e) { // reverse
            var $element = $(e.target.triggerElement());
            var order = $element.data('order') - 1;
            $('.events-time span').text($('.event-item[data-order="' + order + '"] h3').data('time'));
            $('.events-wrapper .event-item').removeClass('event-current');
            $element.addClass('event-current');
            //location.hash = '#' + $(e.target.triggerElement()).attr('id');
        })
        .addTo(controller);
});



$('.events-increment i').on('click', function() {
    var increment = -1;
    var offset = 100;
    if ($(this).hasClass('events-down')) {
        increment = 1;
        offset = 98;
    }
    var order = $('.event-current').data('order');
    var $nextElement = $('.event-item[data-order="' + (order + increment) + '"]');
    if ($nextElement.length) {
        $('html, body').stop(true).animate({
            scrollTop: $nextElement.offset().top - offset
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