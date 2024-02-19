import $ from 'jquery';
import { setMaxCount, mapErrorsToForm } from '../global';

Report();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-report')) {
        Report();
    }
});

function Report() {

    setMaxCount('.timelineReport');

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
    $('.modal-report>.modal-buttons>button').addClass('loading').prop("disabled", true);
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        encode: true,
    }).done(function(response) {
        $('.modal-report>.modal-buttons').hide();
        if (response.success) {
            $('.timelineReport').html('<p>Your report has been submitted. You may now <a href="#" rel="modal:close">close this window</a>.</p>');
        } else {
            $('.timelineReport').html('<p>Sorry, there has been an error. Please try again later.</p>');
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        var errorData = JSON.parse(jqXHR.responseText);
        mapErrorsToForm(errorData.errors, $('.timelineReport'));
        $('.modal-report>.modal-buttons>button').removeClass('loading').prop("disabled", false);
    }).always(function() {
        // Always run after .done() or .fail()
    });
});