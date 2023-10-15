import $ from 'jquery';

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
        encode: true,
    }).done(function(response) {
        // show "saved!" for a few seconds
        if ($('.visibility').length) {
            $('.visibility>span>strong').fadeOut('fast', function() {
                if (response.result == 3) {
                    $('.visibility>span>strong').html('<i class="fa-regular fa-eye public"></i>Public');
                } else if (response.result == 2) {
                    $('.visibility>span>strong').html('<i class="fa-regular fa-eye"></i>Unlisted');
                } else if (response.result == 1) {
                    $('.visibility>span>strong').html('<i class="fa-regular fa-eye-slash"></i>Private');
                } else {
                    $('.visibility>span>strong').html('<i class="fa-brands fa-firstdraft"></i>Draft');
                }
                $('.visibility>span>strong').fadeIn();
            });
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
    });
});