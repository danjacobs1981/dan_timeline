import $ from 'jquery';

// update settings ajax
$(document).on('click', '.timelineSettings button', function(e) {
    var timeline_id = $(this).data('id');
    var $form = $('.timelineSettings');
    // show spinner on button
    $('.timelineSettings button').prop("disabled", true);
    var data = {
        'title': $('.timelineSettings #title').val(),
        'map': +$('.timelineSettings #map').prop('checked'), // boolean (checkbox) - the + makes integers
        'comments': +$('.timelineSettings #comments').prop('checked'),
        'comments_event': +$('.timelineSettings #comments_event').prop('checked'),
        'filter': +$('.timelineSettings #filter').prop('checked'),
        'social': +$('.timelineSettings #social').prop('checked'),
        'collab': +$('.timelineSettings #collab').prop('checked'),
        'profile': +$('.timelineSettings #profile').prop('checked'),
        'adverts': +$('.timelineSettings #adverts').prop('checked'),
    }
    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/settings',
        data: data,
        dataType: 'json',
    }).done(function(response) {
        $('.timelineSettings button').text('Saved!');
        if ($('.edit__section h1').length) {
            $('.edit__section h1').text(data['title']);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        var errorData = JSON.parse(jqXHR.responseText);
        mapErrorsToForm(errorData.errors, $form);
        $('.timelineSettings button').prop("disabled", false);
    }).always(function() {

    });
    e.preventDefault();
});

// detect settings change
$('.timelineSettings').on('input', ':input', function(e) {
    var $settings = $(e.delegateTarget);
    $settings.find(':input').each(function() {
        var $this = $(this);
        if ($this.val() === $this.data('value')) {
            return;
        }
        $('.timelineSettings button').prop("disabled", false).text('Update Settings');
        return false;
    });
});