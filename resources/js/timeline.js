$('#filters').html($('.filters').clone());

/* header buttons */
$('.header__options-share').on('click', function() {
    $('#modal-share').modal();
});