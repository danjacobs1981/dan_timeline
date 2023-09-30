// update privacy ajax
$(document).on('click', 'input[name="privacy"]', function() {
    var timeline_id = $(this).data('id');
    var privacy = $(this).val();
    $('.privacy-draft').hide();
    if (privacy === '1') {
        $('.privacy-share').show();
    } else {
        $('.privacy-share').hide();
    }
    var data = {
        'privacy': privacy,
    }
    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/privacy',
        data: data,
        dataType: 'json',
        success: function(response) {
            // console.log(response.status);
            if (response.status === 200) {
                // show "saved!" for a few seconds

            } else {
                // show "error!" for a few seconds

            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});