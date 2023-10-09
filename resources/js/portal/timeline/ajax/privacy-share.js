import Tagify from '@yaireo/tagify'

rerun();

$(document).on($.modal.OPEN, function() {
    rerun();
});

function rerun() {
    var input = document.querySelector('.tagify-privacy-share'),
        tagify = new Tagify(input, {
            // email address validation
            pattern: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
            tagTextProp: 'email',
            delimiters: ' ',
            trim: false,
            dropdown: {
                position: 'input',
                enabled: 0,
                mapValueTo: 'email'
            }
        })
}

// update privacy share ajax
$('.modal-privacy-share>.modal-buttons>button').on('click', function() {
    console.log("h");
    var timeline_id = $('.modal-privacy-share input[name="timeline-id"]').val();
    var data = {
        'data': tagify.value,
    }
    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/privacy/share',
        data: data,
        dataType: 'json',
        success: function(response) {
            // console.log(response.status);
            if (response.status === 200) {
                $.modal.close();
                var btn = 'Edit';
                if (response.result === null) {
                    $('#timelinePrivacy .privacy-share>p').text('Select people to view your private timeline.');
                    btn = 'Add';
                } else if (response.result.length > 1) {
                    $('#timelinePrivacy .privacy-share>p').text('Privately shared with ' + response.result.length + ' people.');
                } else {
                    $('#timelinePrivacy .privacy-share>p').text('Privately shared with ' + response.result[0]['value']);
                }
                $('#timelinePrivacy .privacy-share>a').text(btn);
            } else {
                // show "error!" for a few seconds
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});