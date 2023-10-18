//import './bootstrap';
import $ from 'jquery';
import ScrollMagic from 'scrollmagic';
import modal from 'jquery-modal';
import.meta.glob([
    '../images/**'
]);

//import 'jquery-sortablejs';
//import * as modal from 'jquery-modal';
//window.$ = $;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// global variables
window.screenSize = getScreenSize();
window.isTouch = testTouch();

//window.controller = new ScrollMagic.Controller();
//window.topHeight = getTopHeight();
/*testMobile();*/
/*getScreenSize();*/
/*topHeight = getTopHeight();*/

$(window).on('resize', function() {
    screenSize = getScreenSize();
    isTouch = testTouch();
    /*testMobile();*/
    /*topHeight = getTopHeight();*/
});

/*function getTopHeight() {
    if (!isMobile) {
        return $('#topbar').outerHeight() + $('header').outerHeight();
    } else {
        return $('header').outerHeight();
    }
}*/

/*function testMobile() {
    if (window.matchMedia("(max-width: 767px)").matches) {
        isMobile = true;
        return true;
    } else {
        isMobile = false;
        return false;
    }
}*/

function getScreenSize() {
    if (window.matchMedia("(max-width: 575px)").matches) {
        return 1;
    } else if (window.matchMedia("(max-width: 767px)").matches) {
        return 2;
    } else if (window.matchMedia("(max-width: 991px)").matches) {
        return 3;
    } else if (window.matchMedia("(max-width: 1199px)").matches) {
        return 4;
    } else if (window.matchMedia("(max-width: 1399px)").matches) {
        return 5;
    } else {
        return 6;
    }
}

function testTouch() {
    return ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0);
}

/*function scrollbarStyles(apply) {
    if (apply) {
        if (isMobile || testTouch()) {
            $('body').addClass('no-scroll');
        } else {
            $('body').addClass('no-scrollbar');
        }
    } else {
        $('body').removeClass('no-scroll').removeClass('no-scrollbar');
    }
}*/

/* modals */

$.modal.defaults = {
    blockerClass: 'modal-blocker',
    closeText: '<span class="fa-stack"><i class="fa-solid fa-circle fa-stack-2x"></i><i class="fa-solid fa-xmark fa-stack-1x"></i></span>',
    spinnerHtml: '<div class="dots"><div></div><div></div><div></div><div></div></div>',
    showSpinner: true,
    showClose: true,
    escapeClose: true,
    clickClose: true
};

$(document).on('click', '[data-modal]', function() {
    var modalExtraClass = '';
    if (typeof $(this).data('modal-class') !== 'undefined') {
        modalExtraClass = $(this).data('modal-class');
    }
    var modalSize = '';
    if (typeof $(this).data('modal-size') !== 'undefined') {
        modalSize = $(this).data('modal-size');
    }
    var modalShowClose = true;
    if (typeof $(this).data('modal-showclose') !== 'undefined') {
        modalShowClose = $(this).data('modal-showclose');
    }
    var modalClickClose = true;
    if (typeof $(this).data('modal-clickclose') !== 'undefined') {
        modalClickClose = $(this).data('modal-clickclose');
    }
    $(this).modal({
        modalClass: modalExtraClass + ' ' + modalSize + ' modal',
        showClose: modalShowClose,
        clickClose: modalClickClose
    });
    return false;
});

/*$(document).on($.modal.OPEN, function() {
    scrollbarStyles(true);
});
$(document).on($.modal.BEFORE_CLOSE, function() {
    scrollbarStyles(false);
});*/

/* dropdowns */

$(document).on('click', function(e) {
    e.stopPropagation();
    if (!$(e.target).is('.dropdown-toggle') && !$('.dropdown-toggle').has(e.target).length) { /* target not .dropdown-toggle & target not within .dropdown-toggle */
        //console.log('doc not ddtog');
        closeDropdowns();
    }
});

$(document).on('click', '.dropdown', function(e) {
    e.stopPropagation();
    if ($(e.target).is('a') || $(e.target).closest('a').length) { /* target is 'a' or target within 'a' */
        //console.log('doc a in dd');
        closeDropdowns();
    }
});

$('body').on('click', '.dropdown-toggle', function(e) {
    if ($(this).hasClass('open') && $(e.target).is('i.dropdown-close')) {
        closeDropdowns();
    } else {
        closeDropdowns();
        var $dropdown = $(this).find('.dropdown');
        if (typeof $dropdown.data('backdrop') !== 'undefined') {
            $dropdown.attr('data-backdrop', 'open');
            $('.backdrop').html($dropdown.clone()).show();
            if (screenSize <= 2) {
                $('body').addClass('backdrop-no-scroll');
            }
        }
        $(this).addClass('open');
        $dropdown.show();
    }
});

function closeDropdowns() {
    $('.dropdown').each(function() {
        if (typeof $(this).data('backdrop') !== 'undefined') {
            $(this).attr('data-backdrop', '');
        }
        $(this).hide();
    });
    $('.dropdown-toggle').removeClass('open');
    $('.backdrop').hide().empty();
    $('body').removeClass('backdrop-no-scroll');
}

/* topbar nav */

/*$('#nav_menu').on('change', function(e) {
    scrollbarStyles(true);
});*/
$('.nav-menu-section').on('click', function(e) {
    if ($(e.target).is('.nav-menu-section') || $(e.target).closest('span').length) {
        $('#nav_menu').prop('checked', false);
        /*scrollbarStyles(false);*/
    }
});

/* topbar search */

$('.nav-search > label').on('click', function() {
    setTimeout(function() {
        $(".nav-search-section input").focus();
    }, 100);
});

/* reveal sections */

$('body').on('click', '[data-reveal]', function() {
    var revealId = $(this).data('reveal');
    $('#' + revealId).addClass('revealed');
    /*scrollbarStyles(true);*/
});
$('.reveal').on('click', function(e) {
    if ($(e.target).is('.reveal') || $(e.target).closest('.reveal__close').length) {
        $(this).removeClass('revealed');
        /*scrollbarStyles(false);*/
    }
});

/* action notifications */

if ($('.action').length) {
    $('.action').addClass('show');
    setTimeout(function() {
        $('.action').removeClass('show');
    }, 3000);
}
$(".action>div>span").on("click", function() {
    $('.action').removeClass('show');
});