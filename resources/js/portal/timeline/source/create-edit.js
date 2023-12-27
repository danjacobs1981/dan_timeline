import $ from 'jquery';


CreateEditSource();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'].includes('modal-create-edit-source')) {
        //console.log("CREATE MODAL");
        CreateEditSource();
        //event.stopImmediatePropagation();
    }
});

function CreateEditSource() {

    var $input_url = $('#formSourceCreateEdit input[name="url"]');
    var $input_title = $('#formSourceCreateEdit input[name="title"]');

    if (source_url) {
        $('input#source').val('');
        $input_url.val(source_url);
        autoFillTitle(source_url);
        source_url = null;
    }

    $('.update-title').on('click', function(e) {
        e.preventDefault();
        autoFillTitle($input_url.val());
    });

    $('#formSourceCreateEdit input[name="url"], #formSourceCreateEdit input[name="title"]').on('focus', function() {
        $('#formSourceCreateEdit .control__error').remove();
    });

    function autoFillTitle(url) {
        $('#formSourceCreateEdit .control__error').remove();
        if (isValidUrl(url)) {
            $('#formSourceCreateEdit .control__label[for="title"]').append('<span style="font-weight:400;"> (loading...)</span>');
            $input_title.attr('disabled', 'disabled');
            getTitle(url);
        } else {
            // not valid url
            var $error = $('<p class="control__error">Not a valid URL - please enter a valid URL.</p>');
            $input_url.after($error);
        }
    }

    function getTitle(url) {
        var data = {
            'url': url
        }
        $.ajax({
            url: '/retrieve/title',
            data: data,
            async: true,
            success: function(response) {
                if (response.title) {
                    $input_title.val(response.title);
                } else {
                    var $error = $('<p class="control__error">Cannot retrieve the page name - please manually type a title for this source.</p>');
                    $input_title.after($error);
                }
                $('#formSourceCreateEdit .control__label[for="title"]>span').remove();
                $input_title.removeAttr('disabled');
            },
            error: function(e) {
                console.log('error!');
            }
        });
    }

    function isValidUrl(textval) {
        var urlregex = new RegExp("^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$");
        return urlregex.test(textval);
    }

    var event_id = null;
    if ($('meta[name="event"]').length) {
        event_id = $('meta[name="event"]').attr('content');
    }

    var $form = $('#formSourceCreateEdit');

    $form.on('submit', function(e) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(response) {
            $.modal.close();
            // reload sources list
            loadSources(response.source_id, event_id, sourcesArray);
            //console.log(response.result);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            var errorData = JSON.parse(jqXHR.responseText);
            //console.log(jqXHR.responseText);
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
            var $error = $('<p class="control__error"></p>');
            $error.html(errorData[fieldName]);
            $(this).after($error);
        });
    }

}