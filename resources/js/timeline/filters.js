import $ from 'jquery';

/* filter */
$('#filters input[type="checkbox"]').on('change', function() {
    var f_checked = $('#filters').find('input:checkbox:checked').length;
    if (f_checked > 0) {
        $('.header__options-filters').find('em.count').remove();
        $('.header__options-filters').find('span').after('<em class="count">' + f_checked + '</em>');
        loadEvents(null, true);
    } else {
        $('.header__options-filters').find('em.count').remove();
        loadEvents(null, false);
    }
});

$('#filters .filter__clear').on('click', function() {
    $('#filters').removeClass('revealed').find('input:checkbox:checked').prop('checked', false);
    $('.header__options-filters').find('em.count').remove();
    $('body').removeClass('no-scroll').removeClass('no-scrollbar');
    loadEvents(null, false);
});

$('#filters .filter__show').on('click', function() {
    $('#filters').removeClass('revealed');
    $('body').removeClass('no-scroll').removeClass('no-scrollbar');
});