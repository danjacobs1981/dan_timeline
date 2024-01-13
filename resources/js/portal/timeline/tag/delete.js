import $ from 'jquery';

// tag delete ajax
$(document).on('click', '.modal-timeline-tag-delete>.modal-buttons>button', function() {

    var timeline_id = parseInt($('.modal-timeline-tag-delete input[name="timeline_id"]').val());
    var tag_id = parseInt($('.modal-timeline-tag-delete input[name="tag_id"]').val());

    var event_id = null;

    if ($('meta[name="event"]').length) {
        event_id = $('meta[name="event"]').attr('content');
    }

    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/tags/' + tag_id + '/delete',
        dataType: 'json',
        encode: true,
        success: function(response) {
            // console.log(response.status);
            if (response.status === 200) {
                $.modal.close();
                $.modal.close();
                var i = $.inArray(tag_id, tagsArray);
                if (i >= 0) {
                    tagsArray.splice(i, 1);
                }
                loadTags(null, event_id, tagsArray);
            } else {
                // show "error!" for a few seconds
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });

});