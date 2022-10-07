$('.header__options-filters').on('click', function() {
    $('#filters').addClass('reveal');
});
$('#filters').on('click', function(e) {
    if ($(e.target).is('#filters') || $(e.target).closest('span').length) {
        $(this).removeClass('reveal');
    }
});


$('.dropdown-toggle input[type="checkbox"]').on('change', function() {
    var $dropdown = $(this).closest('.dropdown-toggle');
    /*var dd_checked = $dropdown.find('input:checkbox:checked:not(.more)').length;*/
    var dd_checked = $dropdown.find('input:checkbox:checked').length;

    /*if ($dropdown.find('.more').length && dd_checked > 0) {
        $dropdown.find('.more').prop('checked', true);
    } else {
        $dropdown.find('.more').prop('checked', false);
    }*/

    if ($dropdown.hasClass('filter-checkboxes') && dd_checked > 0) {
        $dropdown.find('.count').remove();
        $dropdown.find('span').after('<span class="count">' + dd_checked + '</span>');
    } else {
        $dropdown.find('.count').remove();
    }

});