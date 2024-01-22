import $ from 'jquery';
import Tagify from '@yaireo/tagify'

var tags = [];

PrivacyShare();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-timeline-privacy-share')) {
        //console.log("PRIVACY MODAL");
        PrivacyShare();
        //event.stopImmediatePropagation();
    }
});

function PrivacyShare() {
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
        });
    tags = tagify.value;
}

// update privacy share ajax
$(document).on('click', '.modal-timeline-privacy-share>.modal-buttons>button', function() {
    var timeline_id = parseInt($('.modal-timeline-privacy-share input[name="timeline_id"]').val());
    var data = {
        'data': tags
    }
    $.ajax({
        type: 'PUT',
        url: '/timelines/' + timeline_id + '/privacy/share',
        data: data,
        dataType: 'json',
        encode: true,
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