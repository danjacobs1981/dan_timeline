$('.header__options-filters').on('click', function() {
    $('#filters').addClass('reveal');
});
$('#filters').on('click', function(e) {
    if ($(e.target).is('#filters') || $(e.target).closest('span').length) {
        $(this).removeClass('reveal');
    }
});