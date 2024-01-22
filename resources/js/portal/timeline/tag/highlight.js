import $ from 'jquery';

CreateEditHighlight();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'] && modal['options']['modalClass'].includes('modal-create-edit-highlight')) {
        //console.log("CREATE MODAL");
        CreateEditHighlight();
        //event.stopImmediatePropagation();
    }
});

function CreateEditHighlight() {

    var tag_id = parseInt($('#tagHighlight').data('id'));

    var tagData = tagsArray.filter(function(tag) {
        return tag.id == tag_id;
    });

    if (tagData[0].highlight) {
        $('#tagHighlight>span').show();
    }

    $('#tagHighlight li').each(function() {
        if ($(this).data('color') == tagData[0].color) {
            $(this).addClass('active');
        }
    });

    $('#tagHighlight li').on('click', function() {
        $('.tags-list li[data-id="' + tag_id + '"]').addClass('highlighted');
        $('.tags-list li[data-id="' + tag_id + '"]>em>a>i').removeClass('fa-regular').addClass('fa-solid').css('color', $(this).attr('data-color'));
        tagData[0].highlight = 1;
        tagData[0].color = $(this).attr('data-color');
        HighlightUpdate();
        $.modal.close();
    });

    $('#tagHighlight>span>em').on('click', function() {
        $('.tags-list li[data-id="' + tag_id + '"]').removeClass('highlighted');
        $('.tags-list li[data-id="' + tag_id + '"]>em>a>i').removeClass('fa-solid').addClass('fa-regular').css('color', '');
        tagData[0].highlight = 0;
        tagData[0].color = null;
        HighlightUpdate();
        $.modal.close();
    });

}