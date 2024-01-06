import $ from 'jquery';
import Sortable from 'sortablejs';

var closestLocal;
var closestLocalAfter;

// needs to be global function as called from a modal
window.loadEvents = function(reload, timeline_id, event_id) {
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
                // https://www.codehim.com/demo/jquery-sortable-js/
                // https://github.com/SortableJS/Sortable
                $('.sortable').each(function(index, value) {
                    new Sortable(this, {
                        handle: '.handle',
                        animation: 150,
                        ghostClass: 'event--ghost',
                        onSort: function(evt) {
                            //console.log(evt.item.dataset.id);
                        },
                        onMove: function(evt) {
                            //console.log(evt.dragged.dataset.id);
                            closestLocal = evt.related.dataset.local;
                            closestLocalAfter = evt.willInsertAfter;
                        },
                        onEnd: function(evt) {
                            //console.log('my id: ' + evt.item.dataset.id);
                            //console.log('local: ' + closestLocal + ' | put after:' + closestLocalAfter);
                            if (typeof closestLocal != 'undefined') {
                                $('#events-tab .loading').show();
                                var data = {
                                        'id': evt.item.dataset.id,
                                        'local': closestLocal,
                                        'local_after': closestLocalAfter,
                                    }
                                    //console.log(data);
                                $.ajax({
                                    url: '/timelines/' + $('meta[name="timeline"]').attr('content') + '/reorder',
                                    type: 'PUT',
                                    data: data,
                                    dataType: 'json',
                                    encode: true,
                                    success: function(response) {
                                        //console.log(response);
                                        loadEvents(true, response.timeline_id, response.event_id);
                                    },
                                    error: function(xhr) {
                                        console.log(xhr.responseText);
                                    }
                                });
                            }
                        },
                    });
                });
                // highlights new/edited event
                if (event_id) {
                    var $event = $('#events').find('.event[data-id="' + event_id + '"]');
                    $event.addClass('highlight');
                    setTimeout(function() {
                        $event.removeClass('highlight');
                    }, 8000);
                    if (reload) {
                        $event.closest('.time').prop('open', true);
                        $event.closest('.day').prop('open', true);
                        $event.closest('.month').prop('open', true);
                        $event.closest('.year').prop('open', true);
                    }
                }
                if (timeline_id) {
                    if (reload) { // reload means date/time change
                        // processes/reorders in the background
                        $.ajax({
                            type: 'PUT',
                            url: '/timelines/' + timeline_id + '/process',
                            dataType: 'json',
                            encode: true
                        });
                    }
                }
            });
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
}

window.sourcesArray = [];

window.loadSources = function(source_id, event_id, sourcesArrayExists) {
    $('.sources-list').html();
    $('.sources-loading').show();
    var placement = 'timeline';
    if (event_id) {
        placement = 'event';
    }
    var sort = $('#' + placement + 'SourceSort').val();
    var data = { event_id: event_id, sort: sort };
    $.ajax({
        url: '/timelines/' + $('meta[name="timeline"]').attr('content') + '/sources',
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(data) {
            $('.sources-list').html(data['sources_html']).promise().done(function() {
                $('.sources-intro').html(data['sources_count']);
                if (event_id) {
                    if ($.isEmptyObject(sourcesArrayExists) && $('input[name="sources_changed"]').val() == 0) {
                        sourcesArray = data.sources_saved;
                    } else {
                        sourcesArray = sourcesArrayExists;
                    }
                    $('.sources-intro>span').text(sourcesArray.length);
                    //console.log(sourcesArray);
                    $('.eventSources .sources-list li').each(function() {
                        var source_id = $(this).data('id');
                        if ($.inArray(source_id, sourcesArray) !== -1) {
                            $(this).addClass('active').prepend('<label class="control__label"><input type="checkbox" checked><div></div></label>');
                        } else {
                            $(this).prepend('<label class="control__label"><input type="checkbox"><div></div></label>');
                        }
                    });
                    $('#timelineEventCreateEdit').on('change', '.eventSources li input[type="checkbox"]', function(e) {
                        e.stopImmediatePropagation();
                        var $sourceEl = $(this).closest('li');
                        var source_id = $sourceEl.data('id');
                        if (this.checked) {
                            sourcesArray.push(source_id);
                            $sourceEl.addClass('active');
                        } else {
                            var i = $.inArray(source_id, sourcesArray);
                            if (i >= 0) {
                                sourcesArray.splice(i, 1);
                            }
                            $sourceEl.removeClass('active');
                        }
                        $('input[name="sources_changed"]').val(1);
                        $('.sources-intro>span').text(sourcesArray.length);
                        //console.log(sourcesArray);
                    });
                }
                $('.sources-loading').fadeOut();
                // highlights new/edited source
                if (source_id) {
                    var $source = $('.sources-list').find('.source[data-id="' + source_id + '"]');
                    $source.addClass('highlight');
                    setTimeout(function() {
                        $source.removeClass('highlight');
                    }, 8000);
                }
            });
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
}

var topHeight = getTopHeight();
setLayout();
loadEvents(false, null, null);
loadSources(null, null, []);

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
        $(this).html('<i class="fa-regular fa-square-caret-right"></i>Expand all dates').toggleClass('active');
    } else if (($detailsOpen == true) && ($detailsActive == false)) {
        $details.prop('open', true);
        $(this).html('<i class="fa-regular fa-square-caret-up"></i>Contract all dates').toggleClass('active');
    } else {
        $details.prop('open', true);
        $(this).html('<i class="fa-regular fa-square-caret-up"></i>Contract all dates').toggleClass('active');
    };
});

// sources
window.source_url = null;
$('.timelineSources a[data-modal-class="modal-create-edit-source"]').on('click', function() {
    source_url = $('input#timelineSourceURL').val();
});

$('#timelineSourceSort').on('change', function() {
    loadSources(null, null, []);
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

$('header>ul.tabs--timeline a, a.tab').on('click', function() {
    openTab($(this).attr('href'));
});

function openTab(id) {
    $('header>ul.tabs--timeline a').removeClass('active');
    $('header>ul.tabs--timeline a[href="' + id + '"]').addClass('active');
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

// resources



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