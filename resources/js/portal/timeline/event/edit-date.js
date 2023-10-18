import $ from 'jquery';
import { loadEvents } from '../edit.js';

EditEventDate();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'].includes('modal-edit-event-date')) {
        //console.log("DATE MODAL");
        EditEventDate();
        //event.stopImmediatePropagation();
    }
});

function EditEventDate() {

    $(document).on('click', '.modal-edit-event-date>.modal-buttons>a', function(event) {
        var previousEvent = $(this).closest('.modal').find('input#previous').val();
        if (previousEvent == 1) {
            loadEvents();
        }
        event.stopImmediatePropagation();
    });

}