import './bootstrap';

import $ from 'jquery';
window.$ = $;

import * as modal from 'jquery-modal'

window.isMobile = false;

testMobile();

$(window).on('resize', function() {
    testMobile();
});

function testMobile() {
    if (window.matchMedia("(max-width: 767px)").matches) {
        isMobile = true;
        return true;
    } else {
        return false;
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

$('.dropdown-toggle').on('click', function(e) {
    if ($(this).hasClass('open') && $(e.target).is('i.dropdown-close')) {
        closeDropdowns();
    } else {
        closeDropdowns();
        var $dropdown = $(this).find('.dropdown');
        if (typeof $dropdown.data('backdrop') !== 'undefined') {
            $dropdown.attr('data-backdrop', 'open');
            $('.backdrop').html($dropdown.clone()).show();
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
}

/* topbar nav */
$('.nav-menu-section').on('click', function(e) {
    if ($(e.target).is('.nav-menu-section')) {
        $('#nav_menu').prop('checked', false);
    }
});
$('.nav-menu-section > nav > span').on('click', function() {
    $('#nav_menu').prop('checked', false);
});

/* topbar search */
$('.nav-search > label').on('click', function() {
    setTimeout(function() {
        $(".nav-search-section input").focus();
    }, 100);
});