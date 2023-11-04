import $ from 'jquery';

EditEventDate();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'].includes('modal-edit-event-date')) {
        //console.log("DATE MODAL");
        EditEventDate();
        //event.stopImmediatePropagation();
    }
});

function EditEventDate() {



}