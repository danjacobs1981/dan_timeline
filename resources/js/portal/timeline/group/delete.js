import $ from 'jquery';

// group delete ajax
$(document).on('click', '.modal-timeline-group-delete>.modal-buttons>button', function() {

    var timeline_id = parseInt($('.modal-timeline-group-delete input[name="timeline_id"]').val());
    var group_id = parseInt($('.modal-timeline-group-delete input[name="group_id"]').val());

    var event_id = null;

    if ($('meta[name="event"]').length) {
        event_id = $('meta[name="event"]').attr('content');
    }

    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/groups/' + group_id + '/delete',
        dataType: 'json',
        encode: true,
        success: function(response) {
            // console.log(response.status);
            if (response.status === 200) {
                $.modal.close();
                $.modal.close();
                /*var i = $.inArray(tag_id, tagsArray);
                if (i >= 0) {
                    tagsArray.splice(i, 1);
                }*/
                $('select[name="group_id"]>option[value=' + response.group_id + ']').remove();
                loadTags(null, null, tagsArray);
            } else {
                // show "error!" for a few seconds
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });

});