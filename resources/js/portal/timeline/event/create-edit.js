import $ from 'jquery';
//import guillotine from 'guillotine';

CreateEditEvent();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'].includes('modal-create-edit-event')) {
        //console.log("CREATE MODAL");
        CreateEditEvent();
        //event.stopImmediatePropagation();
    }
});

function CreateEditEvent() {

    /*var picture = $('#thepicture');  // Must be already loaded or cached!
picture.guillotine({width: 400, height: 300});*/

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

    var $form = $('#formEventCreateEdit');

    $form.on('submit', function(e) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(response) {
            $.modal.close();
            if (response.loadEvents) {
                loadEvents(true, response.timeline_id, response.event_id);
            } else {
                if (response.event_id) {
                    var $event = $('#events').find('.event[data-id="' + response.event_id + '"]');
                    $event.addClass('highlight');
                    setTimeout(function() {
                        $event.removeClass('highlight');
                    }, 8000);
                    if (response.event_title) {
                        $event.find('.details>span').text(response.event_title);
                    }
                }
            }
            //console.log(response.result);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            var errorData = JSON.parse(jqXHR.responseText);
            mapErrorsToForm(errorData.errors);
            //console.log(errorData);
            //console.log(textStatus);
            //console.log(errorThrown);
        }).always(function() {
            // Always run after .done() or .fail()
        });
        e.preventDefault();
    });

    function mapErrorsToForm(errorData) {
        // reset things!
        $form.find('.control__error').remove();
        $form.find(':input').each(function() {
            var fieldName = $(this).attr('name');
            if (!errorData[fieldName]) {
                // no error!
                return;
            }
            var $error = $('<span class="control__error"></span>');
            $error.html(errorData[fieldName]);
            $(this).after($error);
        });
    }

}






/*let map;

function initMap() {
    map = new google.maps.Map(document.getElementById("gmap"), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 8,
        mapTypeId: "roadmap",
        disableDefaultUI: true,
        options: {
            gestureHandling: 'greedy'
        }
    });
}

window.initMap = initMap;*/