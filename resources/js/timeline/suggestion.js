import $ from 'jquery';
import { setMaxCount, mapErrorsToForm } from '../global';

Suggestion();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-suggestion')) {
        Suggestion();
    }
});

function Suggestion() {

    setMaxCount('.timelineSuggestion');

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
    $('.modal-suggestion>.modal-buttons>button').addClass('loading').prop("disabled", true);
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        encode: true,
    }).done(function(response) {
        if (response.success) {
            $('.modal-suggestion>.modal-buttons').hide();
            $('.timelineSuggestion').html('<p>Your suggestion has been submitted. You may now <a href="#" rel="modal:close">close this window</a>.</p>');
        } else {
            $('#modal-signup').modal({
                modalClass: 'modal-login modal-md'
            });
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        var errorData = JSON.parse(jqXHR.responseText);
        mapErrorsToForm(errorData.errors, $('.timelineSuggestion'));
        $('.modal-suggestion>.modal-buttons>button').removeClass('loading').prop("disabled", false);
    }).always(function() {
        // Always run after .done() or .fail()
    });
});