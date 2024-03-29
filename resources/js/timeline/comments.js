import $ from 'jquery';
import { timeline_id } from './scripts';

$(function() {


});

// begin comment
$('.comments-add div.grow-wrap').on('click', function() {
    $('.comments-add').addClass('open');
});

// cancel comment
$('.comments-add>footer>a').on('click', function(e) {
    e.preventDefault();
    $('.comments-add').removeClass('open');
    $('.comments-add>.grow-wrap').attr('data-replicated-value', '');
    $('.comments-add textarea').val('');
});

/* like comment */
$('#comments').on('click', '.comment>footer>div', function() {
    var $el = $(this);
    var comment_id = $el.closest('.comment').data('id');
    $el.addClass('loading');
    $.ajax({
        type: 'POST',
        url: '/timeline/' + timeline_id + '/' + comment_id + '/like',
        dataType: 'json',
    }).done(function(response) {
        window.setTimeout(function() {
            $el.removeClass('loading');
            if (response.success) {
                if (response.increment) {
                    $el.addClass('comment-liked').removeClass('comment-like').find('span').text(response.count);
                } else {
                    $el.addClass('comment-like').removeClass('comment-liked').find('span').text(response.count);
                }
            } else {
                $('#modal-signup').modal({
                    modalClass: 'modal-login modal-md'
                });
            }
        }, 200);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR.responseText);
    }).always(function() {
        // Always run after .done() or .fail()
    });
});