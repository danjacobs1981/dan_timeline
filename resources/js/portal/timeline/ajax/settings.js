// update settings ajax
$(document).on('click', '#timelineSettings>button', function(e) {
    e.preventDefault();
    var timeline_id = $(this).data('id');
    // show spinner on button
    $('#timelineSettings>button').prop("disabled", true);
    var data = {
        'title': $('#title').val(),
        'comments': +$('#comments').prop('checked'), // boolean (checkbox) - the + makes integers
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

            } else {
                // show "error!" for a few seconds
                $('#timelineSettings>button').prop("disabled", false);
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
        $('#timelineSettings>button').prop("disabled", false);
        return false;
    });
});