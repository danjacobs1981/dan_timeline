$(function() {





});

$('.header__options-comments').on('click', function() {
    $('#comments').addClass('reveal');
});
$('#comments').on('click', function(e) {
    if ($(e.target).is('#comments') || $(e.target).closest('span').length) {
        $(this).removeClass('reveal');
    }
});