import $ from 'jquery';
import Sortable from 'sortablejs';

// needs to be global function as called from a modal
window.loadEvents = function() {
    $('#events-tab>#events').html();
    $('#events-tab>.loading').show();
    $.ajax({
        url: '/timelines/' + $('meta[name="timeline"]').attr('content') + '/events',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#events-tab>#events').html(data['events_html']).promise().done(function() {
                $('#events-tab>header>span').text(data['events_count']);
                $('#events-tab>.loading').fadeOut();
                console.log("events loaded");
            });
        }
    });
}

var previousEvent = null;

var sortable = new Sortable(events, {
    handle: '.handle',
    animation: 150,
    ghostClass: 'event--ghost',
    onSort: function(evt) {
        //console.log(evt.item.dataset.id);
    },
    onMove: function(evt) {
        //console.log(evt.dragged.dataset.id);
        //console.log(evt.related.dataset.id);
        previousEvent = evt.related.dataset.id;

    },
    onEnd: function(evt) {
        var url = '/timelines/' + $('meta[name="timeline"]').attr('content') + '/events/' + evt.item.dataset.id + '/edit/date';
        $('div[data-id="' + evt.item.dataset.id + '"] a.change-date').attr('href', url + '/' + previousEvent).trigger('click');
        $('div[data-id="' + evt.item.dataset.id + '"] a.change-date').attr('href', url);
        //console.log(evt.item.dataset.id);
        //console.log('dropped past: ' + previousEvent);
    },
});

var topHeight = getTopHeight();
setLayout();
loadEvents();

$(window).on('resize', function() {
    topHeight = getTopHeight();
    setLayout();
});

// header tabs

if ($(window.location.hash).length) {
    openTab(window.location.hash);
} else {
    openTab('#' + $('section.edit__tab:first').attr('id'));
}

$('.edit__section header>ul a, a.tab').on('click', function() {
    openTab($(this).attr('href'));
});

// visibility
$('.visibility>span').on('click', function(e) {
    e.preventDefault();
    $('.visibility-options').show();
});

$('.visibility-options>span>a').on('click', function(e) {
    e.preventDefault();
    $('.visibility-options').hide();
});

function openTab(id) {
    $('.edit__section header>ul a').removeClass('active');
    $('.edit__section header>ul a[href="' + id + '"]').addClass('active');
    $('section.edit__tab').hide();
    var activeTab = $(id);
    $(activeTab).fadeIn();
}

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