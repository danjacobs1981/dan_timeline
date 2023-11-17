import $ from 'jquery';

EditEventLocation();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'].includes('modal-edit-event-location')) {
        //console.log("LOCATION MODAL");
        EditEventLocation();
        //event.stopImmediatePropagation();
    }
});

function EditEventLocation() {

    // this will pass data to the date modal

}