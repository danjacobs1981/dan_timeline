import './bootstrap';

import $ from 'jquery';
window.$ = $;

import * as modal from 'jquery-modal';

import ScrollMagic from 'scrollmagic';

import.meta.glob([
    '../images/**'
]);

window.isMobile = false;
window.isTouch = testTouch();
window.topbarHeight = getTopbarHeight();
window.headerHeight = getHeaderHeight();
window.urlParams = new URLSearchParams(window.location.search);
window.controller = new ScrollMagic.Controller();

testMobile();

$(window).on('resize', function() {
    testMobile();
    topbarHeight = getTopbarHeight();
    headerHeight = getHeaderHeight();
});

function getTopbarHeight() {
    return $('#topbar').outerHeight();
}

function getHeaderHeight() {
    return $('header').outerHeight();
}

function testMobile() {
    if (window.matchMedia("(max-width: 767px)").matches) {
        isMobile = true;
        return true;
    } else {
        isMobile = false;
        return false;
    }
}

function testTouch() {
    return ('ontouchstart' in window) ||
        (navigator.maxTouchPoints > 0) ||
        (navigator.msMaxTouchPoints > 0);
}

function scrollbarStyles(apply) {
    if (apply) {
        if (testMobile()) {
            $('body').addClass('no-scroll');
        } else {
            if (testTouch()) {
                $('body').addClass('no-scroll');
            } else {
                $('body').addClass('no-scrollbar');
            }
        }
    } else {
        $('body').removeClass('no-scroll').removeClass('no-scrollbar');
    }
}

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

$('[data-modal]').on('click', function() {
    var modalExtraClass = '';
    if (typeof $(this).data('modal_class') !== 'undefined') {
        modalExtraClass = $(this).data('modal_class');
    }
    var modalSize = '';
    if (typeof $(this).data('modal_size') !== 'undefined') {
        modalSize = $(this).data('modal_size');
    }
    var modalClose = true;
    if (typeof $(this).data('modal_close') !== 'undefined') {
        modalClose = $(this).data('modal_close');
    }
    $(this).modal({
        modalClass: modalExtraClass + ' ' + modalSize + ' modal',
        showClose: modalClose
    });
    return false;
});

$(document).on($.modal.OPEN, function() {
    scrollbarStyles(true);
});
$(document).on($.modal.BEFORE_CLOSE, function() {
    scrollbarStyles(false);
});

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
            if (testMobile()) {
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
    $('body').removeClass('backdrop-no-scroll').removeClass('backdrop-no-scrollbar');
}

/* topbar nav */
$('#nav_menu').on('change', function(e) {
    scrollbarStyles(true);
});
$('.nav-menu-section').on('click', function(e) {
    if ($(e.target).is('.nav-menu-section') || $(e.target).closest('span').length) {
        $('#nav_menu').prop('checked', false);
        scrollbarStyles(false);
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
    scrollbarStyles(true);
});
$('.reveal').on('click', function(e) {
    if ($(e.target).is('.reveal') || $(e.target).closest('.reveal__close').length) {
        $(this).removeClass('revealed');
        scrollbarStyles(false);
    }
});