import ScrollMagic from 'scrollmagic';

var controller = new ScrollMagic.Controller();

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
    var offset = -81;
    if (isMobile) {
        offset = -73;
    }
    new ScrollMagic.Scene({
            offset: offset,
            triggerElement: $(this)[0],
        })
        .triggerHook(0)
        .on('enter', function(e) {
            $('.events-time span').text($(e.target.triggerElement()).data('time'));
        })
        .on('leave', function(e) {
            if ($(e.target.triggerElement()).closest('.event-item').prev().find('h3').length > 0) {
                $('.events-time span').text($(e.target.triggerElement()).closest('.event-item').prev().find('h3').data('time'));
            } else if ($(e.target.triggerElement()).closest('section').prev().find('h3').length > 0) {
                $('.events-time span').text($(e.target.triggerElement()).closest('section').prev().find('h3').data('time'));
            }

        })

    .addTo(controller);
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