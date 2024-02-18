import $ from 'jquery';

Suggestion();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-suggestion')) {
        Suggestion();
    }
});

function Suggestion() {

}

// suggestion ajax
$(document).on('click', '.modal-suggestion>.modal-buttons>button', function() {
    var timeline_id = parseInt($('.modal-suggestion input[name="timeline_id"]').val());
    var url = '/timeline/' + timeline_id + '/suggestion';
    if ($('.modal-suggestion input#event_id').is(':checked')) {
        url = '/timeline/' + timeline_id + '/suggestion/' + $('.modal-suggestion input#event_id').val();
    }
    var data = {
        'anonymous': +$('.modal-suggestion #anonymous').prop('checked'),
        'comments': $('.modal-suggestion textarea[name="comments"]').val()
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
                $('#modal-signup').modal({
                    modalClass: 'modal-login modal-md'
                });
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
        }
    });
});