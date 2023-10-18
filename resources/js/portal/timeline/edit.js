import $ from 'jquery';
import Sortable from 'sortablejs';

export function loadEvents() {
    $('#events-tab>div').html('loading');
    $.ajax({
        url: '/timelines/' + $('meta[name="timeline"]').attr('content') + '/events',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#events-tab>div').html(data['events_html']).promise().done(function() {

                console.log(data['events_count']);

            });
        }
    });
}

var previous = null;

var sortable = new Sortable(example1, {
    handle: '.handle',
    animation: 150,
    ghostClass: 'blue-background-class',
    onSort: function(evt) {
        //console.log(evt.item.dataset.id);

    },
    onMove: function(evt) {
        //console.log(evt.dragged.dataset.id);
        //console.log(evt.related.dataset.id);
        previous = evt.related.dataset.id;

    },
    onEnd: function(evt) {
        var url = '/timelines/' + $('meta[name="timeline"]').attr('content') + '/events/' + evt.item.dataset.id + '/edit/date';
        $('div[data-id=83375] a.change-date').attr('href', url + '/' + previous).trigger('click');
        $('div[data-id=83375] a.change-date').attr('href', url);
        //console.log(evt.item.dataset.id);
        //console.log('dropped past: ' + previous);
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
    } else {
        $('.edit__section>div>section').css({
            'height': 'auto'
        });
        $('.edit__events').appendTo($('.edit__section>div>section'));
    }
}