// update settings ajax
$(document).on('click', '#timelineSettings button[type="submit"]', function(e) {
    e.preventDefault();
    var timeline_id = $(this).data('id');
    // show spinner on button
    $('#timelineSettings button[type="submit"]').prop("disabled", true);
    var data = {
        'title': $('#timelineSettings #title').val(),
        'map': +$('#timelineSettings #map').prop('checked'), // boolean (checkbox) - the + makes integers
        'comments': +$('#timelineSettings #comments').prop('checked'),
        'comments_event': +$('#timelineSettings #comments_event').prop('checked'),
        'filter': +$('#timelineSettings #filter').prop('checked'),
        'social': +$('#timelineSettings #social').prop('checked'),
        'collab': +$('#timelineSettings #collab').prop('checked'),
        'profile': +$('#timelineSettings #profile').prop('checked'),
        'adverts': +$('#timelineSettings #adverts').prop('checked'),
    }
    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/settings',
        data: data,
        dataType: 'json',
        success: function(response) {
            // console.log(response.status);
            if (response.status === 200) {
                // show "saved!" for a few seconds
                $('#timelineSettings button[type="submit"]').text('Saved!');
                if ($('.edit__section h1').length) {
                    $('.edit__section h1').text(data['title']);
                }
            } else {
                // show "error!" for a few seconds
                $('#timelineSettings button[type="submit"]').prop("disabled", false);
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});

// detect settings change
$('#timelineSettings').on('input', ':input', function(e) {
    var $settings = $(e.delegateTarget);
    $settings.find(':input').each(function() {
        var $this = $(this);
        if ($this.val() === $this.data('value')) {
            return;
        }
        $('#timelineSettings button[type="submit"]').prop("disabled", false).text('Update Settings');
        return false;
    });
});