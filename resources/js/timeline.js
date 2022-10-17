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
    var scene = new ScrollMagic.Scene({
            offset: -84, // header + padding?
            triggerElement: $(this)[0],
            triggerHook: 0, // on enter
            duration: $(this).height() //  height of section
        })
        .setClassToggle('.events', $(this).data('period'))
        .addTo(controller);
});*/

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