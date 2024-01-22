import $ from 'jquery';

EditEventDateLocation();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-edit-event-date')) {
        //console.log("DATE MODAL");
        EditEventDateLocation();
        //event.stopImmediatePropagation();
    }
});

function EditEventDateLocation() {

    // tabs
    openTab('#' + $('section.event__tab:first').attr('id'));
    $('header>ul.tabs--event a, a.tab').on('click', function() {
        openTab($(this).attr('href'));
    });

    function openTab(id) {
        $('header>ul.tabs--event a').removeClass('active');
        $('header>ul.tabs--event a[href="' + id + '"]').addClass('active');
        $('section.event__tab').hide();
        var activeTab = $(id);
        $(activeTab).show();
    }

    var $form = $('#formEventEditDate');

    $form.on('submit', function(e) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(response) {
            if (response.loadEvents) {
                $.modal.close();
                loadEvents(true, response.timeline_id, response.event_id);
            } else {
                // show error that date hasn't changed
            }
            //console.log(response.result);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            //var errorData = JSON.parse(jqXHR.responseText);
            //console.log(errorData);
            mapErrorsToForm(errorData.errors, $form);
        }).always(function() {
            // Always run after .done() or .fail()
        });
        e.preventDefault();
    });

}