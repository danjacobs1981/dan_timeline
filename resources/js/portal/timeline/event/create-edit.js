import $ from 'jquery';

CreateEditEvent();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'].includes('modal-create-edit-event')) {
        //console.log("CREATE MODAL");
        CreateEditEvent();
        //event.stopImmediatePropagation();
    }
});

function CreateEditEvent() {


    var event_id = null;
    if ($('meta[name="event"]').length) {
        event_id = parseInt($('meta[name="event"]').attr('content'));
    }

    // sources
    loadSources(null, event_id, []);
    $('#eventSourceSort').on('change', function() {
        loadSources(null, event_id, sourcesArray);
    });

    // add image
    $('input[type=file]').on('change', function() {
        $(this).hide();
        $(this).next().filter('.control__error').remove();
        $('input[name="image_delete"]').val(0);
        var file = this.files[0];
        displayPreview(file);
    });

    // remove image
    $('.image-preview>a').on('click', function(e) {
        e.preventDefault();
        $('input[type="file"]').val('').show();
        $('input[name="image_delete"]').val(1);
        $('.image-preview').hide();
    });

    // position bg image
    $('.image-preview select').on('change', function() {
        $('.' + $(this).data('image') + ' .input-event-image').css('background-position', $(this).val());
    });

    function displayPreview(files) {
        var reader = new FileReader();
        var img = new Image();
        reader.onload = function(e) {
            img.src = e.target.result;
            // file size in kb
            var fileSize = Math.round(files.size / 1024);
            if (fileSize > 4096) {
                $('input[type="file"]').val('').show();
                $('input[name="image_delete"]').val(1);
                $('input[type=file]').after('<span class="control__error">The file size is too large (' + fileSize + 'kb) - please use a different image.</span>');
            } else {
                img.onload = function() {
                    $('.input-event-image').css('background-image', 'url(' + img.src + ')');
                    $('.image-preview select, .image-preview select>option').show();
                    $('.image-preview select').val('50% 50%').trigger('change');
                    if (this.width == this.height) { // square
                        $('select[name="image_thumbnail"]').val('50% 50%').trigger('change').hide();
                    } else if (this.width > this.height) { // wide
                        $('select[name="image_thumbnail"]>option[data-type="tall"]').hide();
                    } else { // tall
                        $('select[name="image_thumbnail"]>option[data-type="wide"]').hide();
                    }
                    $('.image-preview').show();
                };

            }
        };
        reader.readAsDataURL(files);
    }

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
        let data = new FormData(this);
        if (document.getElementById('image').files.length != 0) {
            data.append('image', document.getElementById('image').files[0]); // image upload
        }
        sourcesArray.forEach((item) => data.append("sources[]", item))
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: data,
            contentType: false,
            processData: false,
            cache: false,
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
            var $error = $('<span class="control__error"></span>');
            $error.html(errorData[fieldName]);
            $(this).after($error);
        });
    }

}