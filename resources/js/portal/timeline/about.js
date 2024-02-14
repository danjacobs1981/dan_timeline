import $ from 'jquery';

// add image
$('.timelineAbout input[type=file]').on('change', function() {
    $('.timelineAbout button').prop("disabled", false).text('Update About');
    $(this).hide();
    $(this).next().filter('.control__error').remove();
    $('.timelineAbout input[name="image_delete"]').val(0);
    var file = this.files[0];
    displayPreview(file);
});

// remove image
$('.timelineAbout .image-preview>a').on('click', function(e) {
    e.preventDefault();
    $('.timelineAbout button').prop("disabled", false).text('Update About');
    $('.timelineAbout input[type="file"]').val('').show();
    $('.timelineAbout input[name="image_delete"]').val(1);
    $('.timelineAbout .image-preview').hide();
    $('.timelineAbout .image-preview img').remove();
});

function displayPreview(files) {
    var reader = new FileReader();
    var img = new Image();
    reader.onload = function(e) {
        img.src = e.target.result;
        // file size in kb
        console.log(files);
        var fileSize = Math.round(files.size / 1024);
        if (fileSize > 4096) {
            $('.timelineAbout input[type="file"]').val('').show();
            $('.timelineAbout input[name="image_delete"]').val(1);
            $('.timelineAbout input[type=file]').after('<span class="control__error">The file size is too large (' + fileSize + 'kb) - please use a different image.</span>');
        } else {
            img.onload = function() {
                //$('.timelineAbout .input-timeline-image').css('background-image', 'url(' + img.src + ')');
                // add new img element
                $('.timelineAbout .image-preview>div>div>strong').after('<img src="' + img.src + '" width="380">');
                $('.timelineAbout .image-preview').show();
            };

        }
    };
    reader.readAsDataURL(files);
}

// update about ajax
$(document).on('click', '.timelineAbout button', function(e) {
    var timeline_id = $(this).data('id');
    var $form = $('.timelineAbout');
    $('.timelineAbout button').addClass('loading').prop("disabled", true);
    var data = new FormData();
    data.append('description', $('.timelineAbout #description').val());
    if (document.getElementById('image').files.length != 0) {
        data.append('image', document.getElementById('image').files[0]); // image upload
    }
    $.ajax({
        url: '/timelines/' + timeline_id + '/about',
        data: data,
        type: 'POST',
        contentType: false,
        processData: false,
        cache: false,
    }).done(function(response) {
        $('.timelineAbout button').removeClass('loading').text('Saved!');
    }).fail(function(jqXHR, textStatus, errorThrown) {
        var errorData = JSON.parse(jqXHR.responseText);
        mapErrorsToForm(errorData.errors, $form);
        $('.timelineAbout button').removeClass('loading').prop("disabled", false);
    }).always(function() {

    });
    e.preventDefault();
});

// detect about change - needs work
$('.timelineAbout').on('input', 'textarea', function() {
    $('.timelineAbout button').prop("disabled", false).text('Update About');
});