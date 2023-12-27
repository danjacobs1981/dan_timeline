import $ from 'jquery';

// source delete ajax
$(document).on('click', '.modal-timeline-source-delete>.modal-buttons>button', function() {

    var timeline_id = $('.modal-timeline-source-delete input[name="timeline_id"]').val();
    var source_id = $('.modal-timeline-source-delete input[name="source_id"]').val();

    var event_id = null;
    if ($('meta[name="event"]').length) {
        event_id = $('meta[name="event"]').attr('content');
    }

    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/sources/' + source_id + '/delete',
        dataType: 'json',
        encode: true,
        success: function(response) {
            // console.log(response.status);
            if (response.status === 200) {
                $.modal.close();
                $.modal.close();
                loadSources(null, event_id, sourcesArray);
            } else {
                // show "error!" for a few seconds
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });

});