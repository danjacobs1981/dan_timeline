import $ from 'jquery';
import { loadEvents } from '../edit.js';

runScript();

$(document).on($.modal.OPEN, function() {
    runScript();
});

function runScript() {

    var previous = $('#previous').val();

    console.log(previous);

    $(document).on('click', '.modal-edit-date>.modal-buttons>a', function() {
        if (previous) {
            loadEvents();
        }
    });

    $('[data-date]').each(function() {
        var name = $(this).attr('id');
        $('input[name="date_' + name + '"]').val(this.value);
        if (this.value) {
            $('span.add[data-period="' + name + '"]').hide();
            if (name == 'time') {
                $('span.add[data-period]').hide();
            } else if (name == 'day') {
                $('span.add[data-period="month"], span.add[data-period="year"]').hide();
            } else if (name == 'month') {
                $('span.add[data-period="year"]').hide();
            }
        }
    });

    $('.date_wrapper span[data-period]').on('click', function() {
        var name = $(this).data('period');
        if ($(this).hasClass('add')) {
            $(this).hide();
            $('div.' + name).addClass('date_active');
            if (name == 'month') {
                $('.hidden div.month>input').val($('select[id="month"]').val());
            } else if (name == 'time') {
                $('.hidden div.time>input[name="date_time"]').val($('input[id="time"]').val());
                $('.hidden div.time>input[name="date_time_ampm"]').val($('select[id="time_ampm"]').val());
            } else {
                $('.hidden div.' + name + '>input').val($('input[id="' + name + '"]').val());
            }
        } else if ($(this).hasClass('remove')) {
            $('span.add[data-period="' + name + '"], .date div.' + name + ' span[data-period].add').show();
            $('div.' + name + ', div.' + name + ' .date_active').removeClass('date_active');
            $('.hidden div.' + name + ' input, .hidden div.' + name + ' div.date_active input').val('');
        }
    });

    $('.date input').on('input', function() {
        var name = $(this).attr('id');
        $('input[name="date_' + name + '"]').val(this.value);
    });

    $('.date select').on('change', function() {
        var name = $(this).attr('id');
        $('input[name="date_' + name + '"]').val(this.value);
    });


}