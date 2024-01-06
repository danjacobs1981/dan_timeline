import $ from 'jquery';

// event delete ajax
$(document).on('click', '.modal-timeline-event-delete>.modal-buttons>button', function() {

    var timeline_id = parseInt($('.modal-timeline-event-delete input[name="timeline_id"]').val());
    var event_id = parseInt($('.modal-timeline-event-delete input[name="event_id"]').val());

    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/events/' + event_id + '/delete',
        dataType: 'json',
        encode: true,
        success: function(response) {
            // console.log(response.status);
            if (response.status === 200) {
                $.modal.close();
                $.modal.close();
                loadEvents(true, response.timeline_id, null);
            } else {
                // show "error!" for a few seconds
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });

});