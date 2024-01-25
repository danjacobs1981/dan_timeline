import $ from 'jquery';
import { loadEvents } from './../timeline/scripts';

/* filter */
$('#filters input[type="checkbox"]').on('change', function() {
    var f_checked = $('#filters input:checkbox[name="tag"]:checked').length;
    if (f_checked > 0) {
        $('.events').addClass('events--filtered');
        $('.header__options-filters').find('em.count').remove();
        $('.header__options-filters').find('span').after('<em class="count">' + f_checked + '</em>');
        let tags = [];
        $('#filters input:checkbox[name="tag"]:checked').each(function() {
            tags.push($(this).val());
        });
        loadEvents(null, tags);
    } else {
        $('.events').removeClass('events--filtered');
        $('.header__options-filters').find('em.count').remove();
        loadEvents(null, []);
    }
});

$('#filters .filter__clear').on('click', function() {
    $('.events').removeClass('events--filtered');
    $('#filters').removeClass('revealed').find('input:checkbox:checked').prop('checked', false);
    $('.header__options-filters').find('em.count').remove();
    $('body').removeClass('no-scroll').removeClass('no-scrollbar');
    loadEvents(null, []);
});

$('#filters .filter__show').on('click', function() {
    $('#filters').removeClass('revealed');
    $('body').removeClass('no-scroll').removeClass('no-scrollbar');
});