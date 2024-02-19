import $ from 'jquery';
import { setMaxCount, mapErrorsToForm } from '../../../global';

CreateEditTag();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-create-edit-tag')) {
        //console.log("CREATE MODAL");
        CreateEditTag();
        //event.stopImmediatePropagation();
    }
});

function CreateEditTag() {

    setMaxCount('.modal-create-edit-tag');

    var event_id = null;

    if ($('meta[name="event"]').length) {
        event_id = $('meta[name="event"]').attr('content');
    }

    var $form = $('#formTagCreateEdit');

    $form.on('submit', function(e) {
        $('.btn[form="formTagCreateEdit"]').addClass('loading').prop("disabled", true);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(response) {
            $.modal.close();
            // reload tags list
            loadTags(response.tag_id, event_id, tagsArray);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            var errorData = JSON.parse(jqXHR.responseText);
            mapErrorsToForm(errorData.errors, $form);
            $('.btn[form="formTagCreateEdit"]').removeClass('loading').prop("disabled", false);
        }).always(function() {
            // Always run after .done() or .fail()
        });
        e.preventDefault();
    });

}