import $ from 'jquery';

Report();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-report')) {
        Report();
    }
});

function Report() {

    $('[data-modal-class="modal-suggestion"]').on('click', function() {
        $.modal.close();
    });

}

// report ajax
$(document).on('click', '.modal-report>.modal-buttons>button', function() {
    var timeline_id = parseInt($('.modal-report input[name="timeline_id"]').val());
    var url = '/timeline/' + timeline_id + '/report';
    if ($('.modal-report input#event_id').is(':checked')) {
        url = '/timeline/' + timeline_id + '/report/' + $('.modal-report input#event_id').val();
    }
    var data = {
        'category': $('.modal-report input[name="category"]:checked').val(),
        'comments': $('.modal-report textarea[name="comments"]').val()
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        encode: true,
        success: function(response) {
            if (response.success) {
                $.modal.close();
            } else {
                // show "error!" for a few seconds
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});