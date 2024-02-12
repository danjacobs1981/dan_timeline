import $ from 'jquery';
import Sortable from 'sortablejs';
import { screenSize } from './../../global';

var timeline_id = parseInt($('meta[name="timeline"]').attr('content'));

var closestLocal;
var closestLocalAfter;

// need to be global functions as called from a modal

window.loadEvents = function(reload, timeline_id, event_id) {
    $('#events-tab #events').html();
    $('#events-tab .loading').show();
    $.ajax({
        url: '/timelines/' + timeline_id + '/events',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#events-tab #events').html(data['events_html']).promise().done(function() {
                $('#events-tab header>div>span').text(data['events_count']);
                $('#events-tab .loading').fadeOut();
                // https://www.codehim.com/demo/jquery-sortable-js/
                // https://github.com/SortableJS/Sortable
                $('.sortable-events').each(function(index, value) {
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
                                    url: '/timelines/' + timeline_id + '/reorder',
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
                    }, 3000);
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

window.tagsArray = [];

window.loadTags = function(tag_id, event_id, tagsArrayExists) {
    $('.tags-list').html();
    $('.tags-loading').show();
    var placement = 'timeline';
    if (event_id) {
        placement = 'event';
    }
    var sort = $('#' + placement + 'TagSort').val();
    var data = { event_id: event_id, sort: sort };
    $.ajax({
        url: '/timelines/' + timeline_id + '/tags',
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(data) {
            $('.tags-list').html(data['tags_html']).promise().done(function() {
                $('.timelineTags .tags-intro').text(data['tags_count']);
                if (event_id) {
                    //console.log(data.tags_saved);
                    $('.eventTags .tags-intro').html(data['tags_count_saved']);
                    if ($.isEmptyObject(tagsArrayExists) && $('input[name="tags_changed"]').val() == 0) {
                        tagsArray = data.tags_saved;
                    } else {
                        tagsArray = tagsArrayExists;
                    }
                    tagsArray = removeDuplicateValue(tagsArray);
                    //console.log(tagsArray);
                    $('.eventTags .tags-intro>span').text(Object.keys(tagsArray).length);
                    $('.eventTags .tags-list li').each(function() {
                        var highlight_class = '';
                        var highlight_icon = 'regular';
                        var tag_id = $(this).data('id');
                        var tagData = tagsArray.filter(function(tag) {
                            return tag.id == tag_id;
                        });
                        if (tagData.length > 0) {
                            if (tagData[0].highlight) {
                                highlight_class = 'highlighted';
                                highlight_icon = 'solid';
                            }
                            $(this).addClass('active').addClass(highlight_class).prepend('<label class="control__label"><input type="checkbox" checked><div></div></label><em data-popover="Highlight tag" data-popover-position="top"><i class="fa-' + highlight_icon + ' fa-star"></i></em>');
                        } else {
                            $(this).prepend('<label class="control__label"><input type="checkbox"><div></div></label><em data-popover="Highlight tag" data-popover-position="top"><i class="fa-' + highlight_icon + ' fa-star"></i></em>');
                        }
                    });
                    $('#timelineEventCreateEdit').on('change', '.eventTags li input[type="checkbox"]', function(e) {
                        e.stopImmediatePropagation();
                        var $tagEl = $(this).closest('li');
                        var tag_id = $tagEl.data('id');
                        if (this.checked) {
                            tagsArray.push({ 'id': tag_id, 'highlight': 0 });
                            $tagEl.addClass('active');
                        } else {
                            tagsArray = tagsArray.filter(({ id }) => id !== tag_id);
                            $tagEl.removeClass('active').removeClass('highlighted');
                            $tagEl.find('em>i').removeClass('fa-solid').addClass('fa-regular');
                        }
                        $('input[name="tags_changed"]').val(1);
                        $('.eventTags .tags-intro>span').text(Object.keys(tagsArray).length);
                        //console.log(tagsArray);
                    });
                    $('#timelineEventCreateEdit').on('click', '.eventTags li.active>em', function(e) {
                        e.stopImmediatePropagation();
                        var $tagEl = $(this).closest('li');
                        var tag_id = $tagEl.data('id');
                        var tagData = tagsArray.filter(function(tag) {
                            return tag.id == tag_id;
                        });
                        if ($tagEl.hasClass('highlighted')) {
                            tagData[0].highlight = 0;
                            $tagEl.find('em>i').removeClass('fa-solid').addClass('fa-regular');
                            $tagEl.removeClass('highlighted');
                        } else {
                            tagData[0].highlight = 1;
                            $tagEl.find('em>i').removeClass('fa-regular').addClass('fa-solid');
                            $tagEl.addClass('highlighted');
                        }
                        $('input[name="tags_changed"]').val(1);
                        //console.log(tagsArray);
                    });
                }
                $('.sortable-tags').each(function(index, value) {
                    new Sortable(this, {
                        group: 'shared',
                        sort: false,
                        handle: '.handle',
                        animation: 150,
                        onMove: function(evt) {
                            $('.tags-group').removeClass('hover');
                            $(evt.to).closest('.tags-group').addClass('hover');
                            $(evt.to).children('span').hide();
                        },
                        onEnd: function(evt) {
                            $('.tags-group').removeClass('hover');
                            var group_id = $(evt.to).closest('.sortable-tags').data('id');
                            if (typeof group_id != 'undefined') {
                                $('.tags-loading').show();
                                var data = {
                                        'group_id': group_id
                                    }
                                    //console.log(data);
                                $.ajax({
                                    url: '/timelines/' + timeline_id + '/tags/' + evt.item.dataset.id + '/group',
                                    type: 'PUT',
                                    data: data,
                                    dataType: 'json',
                                    encode: true,
                                    success: function(response) {
                                        //console.log(response);
                                        if (event_id) {
                                            loadTags(null, event_id, tagsArray);
                                        } else {
                                            loadTags(null, null, []);
                                        }
                                    },
                                    error: function(xhr) {
                                        console.log(xhr.responseText);
                                    }
                                });
                            }
                        }
                    })
                });
                $('.tags-loading').fadeOut();
                // highlights new/edited tag
                if (tag_id) {
                    var $tag = $('.tags-list').find('.tag[data-id="' + tag_id + '"]');
                    $tag.addClass('highlight');
                    setTimeout(function() {
                        $tag.removeClass('highlight');
                    }, 3000);
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
        url: '/timelines/' + timeline_id + '/sources',
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(data) {
            $('.sources-list').html(data['sources_html']).promise().done(function() {
                $('.timelineSources .sources-intro').text(data['sources_count']);
                if (event_id) {
                    $('.eventSources .sources-intro').html(data['sources_count_saved']);
                    if ($.isEmptyObject(sourcesArrayExists) && $('input[name="sources_changed"]').val() == 0) {
                        sourcesArray = data.sources_saved;
                    } else {
                        sourcesArray = sourcesArrayExists;
                    }
                    if (source_id) {
                        sourcesArray.push(source_id);
                        $('input[name="sources_changed"]').val(1);
                    }
                    sourcesArray = sourcesArray.filter((item, index) => sourcesArray.indexOf(item) === index); // makes array unique
                    $('.eventSources .sources-intro>span').text(sourcesArray.length);
                    //console.log(sourcesArray);
                    $('.eventSources .sources-list li').each(function() {
                        var source_id = $(this).data('id');
                        if ($.inArray(source_id, sourcesArray) !== -1) {
                            $(this).addClass('active').prepend('<label class="control__label"><input type="checkbox" checked><div></div></label>')
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
                        $('.eventSources .sources-intro>span').text(sourcesArray.length);
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
                    }, 3000);
                }
            });
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
}

window.setMaxCount = function(outerEl = '') {
    $(outerEl + ' [maxlength]').each(function() {
        var $el = $(this);
        var text_max = $el.attr('maxlength');
        var text_length = $el.val().length;
        if ($el.closest('div, form').hasClass('control__multiple')) {
            $el = $(this).closest('div, form');
        }
        $('<p><span>' + text_length + '</span> of ' + text_max + ' characters max.</p>').insertAfter($el);
        var $text_update = $el.next('p').find('span');
        $(this).on('keyup', function() {
            text_length = $(this).val().length;
            if (text_length == text_max) {
                $text_update.css('color', 'var(--danger)');
            } else {
                $text_update.css('color', 'unset');
            }
            $text_update.text(text_length);
        }).on('keyup');
    });
}

window.mapErrorsToForm = function(errorData, $form) {
    $form.find('.control__error').remove();
    $form.find('.control').removeClass('control--error');
    $form.find(':input').each(function() {
        var fieldName = $(this).attr('name');
        if (typeof $(this).data('name') != 'undefined') { // use the actual form field name you want to get errors on
            fieldName = $(this).data('name');
        }
        if (!errorData[fieldName]) {
            // no error!
            return;
        }
        var $error = $('<span class="control__error"></span>');
        $error.html('<i class="fa-solid fa-circle-exclamation"></i>' + errorData[fieldName]);
        $(this).after($error);
        $error.closest('.control').addClass('control--error');
    });
}

$(document).on('focus', '.control input', function() {
    $(this).closest('.control').removeClass('control--error').find('.control__error').remove();
});

var topHeight = getTopHeight();
setLayout();
loadEvents(false, timeline_id, null);
loadTags(null, null, []);
loadSources(null, null, []);
setMaxCount();

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

// tags
$('#timelineTagSort').on('change', function() {
    loadTags(null, null, []);
});

$('.timelineTags button').on('click', function(e) {
    //var timeline_id = $(this).data('id');
    var $form = $('.timelineTags');
    // show spinner on button
    $('.timelineTags button').prop("disabled", true);
    var data = {
        'tag': $('input[name="timeline_tag"]').val(),
        'group_id': $('select[name="timeline_group_id"]').val()
    }
    $.ajax({
        type: 'POST',
        url: '/timelines/' + timeline_id + '/tags',
        data: data,
        dataType: 'json',
    }).done(function(response) {
        $('input[name="timeline_tag"]').val('');
        // reload tags list
        loadTags(response.tag_id, null, tagsArray);
        //console.log(response.result);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        var errorData = JSON.parse(jqXHR.responseText);
        mapErrorsToForm(errorData.errors, $form);
    }).always(function() {
        // Always run after .done() or .fail()
        $('.timelineTags button').prop("disabled", false);
    });
    e.preventDefault();
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

function removeDuplicateValue(myArray) {
    var newArray = [];

    $.each(myArray, function(key, value) {
        var exists = false;
        $.each(newArray, function(k, val2) {
            if (value.id == val2.id) { exists = true };
        });
        if (exists == false && value.id != "") { newArray.push(value); }
    });

    return newArray;
}