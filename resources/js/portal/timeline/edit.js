import $ from 'jquery';
import Sortable from 'sortablejs';

// needs to be global function as called from a modal
window.loadEvents = function() {
    $('#events-tab #events').html();
    $('#events-tab .loading').show();
    $.ajax({
        url: '/timelines/' + $('meta[name="timeline"]').attr('content') + '/events',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#events-tab #events').html(data['events_html']).promise().done(function() {
                $('#events-tab header>div>span').text(data['events_count']);
                $('#events-tab .loading').fadeOut();
                //console.log("events loaded");
                $('.sortable').each(function(index, value) {
                    var sortable = new Sortable(this, {
                        handle: '.handle',
                        animation: 150,
                        ghostClass: 'event--ghost',
                        onSort: function(evt) {
                            //console.log(evt.item.dataset.id);
                        },
                        onMove: function(evt) {
                            //console.log(evt.dragged.dataset.id);
                            //console.log(evt.related.dataset.id);
                            //previousEvent = evt.related.dataset.id;

                        },
                        onEnd: function(evt) {
                            //var url = '/timelines/' + $('meta[name="timeline"]').attr('content') + '/events/' + evt.item.dataset.id + '/edit/date';
                            //$('div[data-id="' + evt.item.dataset.id + '"] a.change-date').attr('href', url + '/' + previousEvent).trigger('click');
                            //$('div[data-id="' + evt.item.dataset.id + '"] a.change-date').attr('href', url);
                            //console.log(evt.item.dataset.id);
                            //console.log('dropped past: ' + previousEvent);
                        },
                    });
                });


            });
        }
    });
}

var topHeight = getTopHeight();
setLayout();
loadEvents();

$(window).on('resize', function() {
    topHeight = getTopHeight();
    setLayout();
});

$('#events-tab header>div>em').on('click', function() {
    var $details = $('#events').find('details');
    var $detailsOpen = $details.prop('open');
    var $detailsActive = $(this).hasClass('active');
    if ($detailsActive == true) {
        $details.prop('open', false);
        $(this).html('<i class="fa-regular fa-square-caret-down"></i>Expand all dates').toggleClass('active');
    } else if (($detailsOpen == true) && ($detailsActive == false)) {
        $details.prop('open', true);
        $(this).html('<i class="fa-regular fa-square-caret-up"></i>Contract all dates').toggleClass('active');
    } else {
        $details.prop('open', true);
        $(this).html('<i class="fa-regular fa-square-caret-up"></i>Contract all dates').toggleClass('active');
    };
});

// header tabs

if ($(window.location.hash).length) {
    if (screenSize > 4 && window.location.hash == '#events-tab') {
        openTab('#' + $('section.edit__tab:first').attr('id'));
    } else {
        openTab(window.location.hash);
    }
} else {
    openTab('#' + $('section.edit__tab:first').attr('id'));
}

$('.edit__section header>ul a, a.tab').on('click', function() {
    openTab($(this).attr('href'));
});

function openTab(id) {
    $('.edit__section header>ul a').removeClass('active');
    $('.edit__section header>ul a[href="' + id + '"]').addClass('active');
    $('section.edit__tab').hide();
    var activeTab = $(id);

    $('html, body').animate({
        scrollTop: $('.edit__tab').offset().top
    }, 50);

    /*if (screenSize > 2) {
        $(activeTab).scrollTop(0);
    } else {
        $(activeTab).scrollTop(200);
    }*/

    $(activeTab).show();
}

// timeline visibility box

$('.visibility>span').on('click', function(e) {
    e.preventDefault();
    $('.visibility-options').show();
});

$('.visibility-options>span>a').on('click', function(e) {
    e.preventDefault();
    $('.visibility-options').hide();
});

// layout functions

function getTopHeight() {
    if (screenSize > 4) {
        return $('#topbar').outerHeight() + $('header').outerHeight();
    } else {
        return $('header').outerHeight();
    }
}

function setLayout() {
    if (screenSize > 4) {
        $('.edit__section>div>section').css({
            'height': 'calc(100vh - ' + topHeight + 'px)'
        });
        $('.edit__events').appendTo($('.edit__section'));
        openTab('#' + $('section.edit__tab:first').attr('id'));
    } else {
        $('.edit__section>div>section').css({
            'height': 'auto'
        });
        $('.edit__events').appendTo($('.edit__section>div>section'));
    }
}