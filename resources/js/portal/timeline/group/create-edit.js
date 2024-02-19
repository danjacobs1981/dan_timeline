import $ from 'jquery';
import { setMaxCount, mapErrorsToForm } from '../../../global';

CreateEditGroup();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-create-edit-group')) {
        //console.log("CREATE MODAL");
        CreateEditGroup();
        //event.stopImmediatePropagation();
    }
});

function CreateEditGroup() {

    setMaxCount('.modal-create-edit-group');

    var event_id = null;

    if ($('meta[name="event"]').length) {
        event_id = $('meta[name="event"]').attr('content');
    }

    var $form = $('#formGroupCreateEdit');

    $form.on('submit', function(e) {
        $('.btn[form="formGroupCreateEdit"]').addClass('loading').prop("disabled", true);
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(response) {
            $.modal.close();
            if (response.update) {
                $('select[name="group_id"]>option[value=' + response.group_id + ']').text(response.group);
            } else {
                $('select[name="group_id"]').append($('<option>').val(response.group_id).text(response.group).attr('selected', 'selected'));
            }
            // reload tags list
            loadTags(null, null, tagsArray);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            var errorData = JSON.parse(jqXHR.responseText);
            mapErrorsToForm(errorData.errors, $form);
            $('.btn[form="formGroupCreateEdit"]').removeClass('loading').prop("disabled", false);
        }).always(function() {
            // Always run after .done() or .fail()
        });
        e.preventDefault();
    });

}