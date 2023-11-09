import $ from 'jquery';

EditEventDate();

$(document).on($.modal.OPEN, function(event, modal) {
    if (modal['options']['modalClass'].includes('modal-edit-event-date')) {
        //console.log("DATE MODAL");
        EditEventDate();
        //event.stopImmediatePropagation();
    }
});

function EditEventDate() {

    // DATE PICKER 
    var $datepicker = $('.control--datepicker');
    var predate = $datepicker.data('predate').toString();
    var daysInMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    $.each(predate.split(/\|/), function(i, val) {
        var period = '';
        if (val) {
            if (i == 0) {
                period = 'year';
            } else if (i == 1) {
                period = 'month';
            } else if (i == 2) {
                period = 'day';
            } else if (i == 3) {
                period = 'time';
            } else if (i == 4) {
                period = 'time_min';
            } else if (i == 5) {
                period = 'time_ampm';
            }
            if (i <= 3) {
                $datepicker.find('.period.' + period).removeClass('add').addClass('active').next('div').addClass('add');
            }
            $('#' + period).val(val);
            updateDateFields(period, val);
        }
    });

    $datepicker.on('click', '.period.add', function() {
        $(this).addClass('active').removeClass('add').next('div').addClass('add');
        updateDateFields($(this).data('period'), $(this).find('[data-date').val());
    });

    $datepicker.on('click', '.period.active>div>span', function() {
        $(this).closest('.period').css('border-color', 'inherit').removeClass('active').addClass('add').nextAll('div').css('border-color', 'inherit').removeClass('active').removeClass('add');
        var period = $(this).closest('.period').data('period');
        $('.date-fields input[name="date_' + period + '"]').val(null).nextAll('input').val(null);
    });

    $datepicker.on('mouseenter', '.period.active>div>span', function() {
        $(this).closest('.period').css('border-color', 'red').nextAll('div.active').css('border-color', 'red');
    });

    $datepicker.on('mouseleave', '.period.active>div>span', function() {
        $('.period').css('border-color', 'inherit');
    });

    $datepicker.on('change', '#month', function() {
        var daysCount = daysInMonth[(this.value * 1) - 1];
        var dayCurrent = $('#day').val();
        $('#day option').show();
        if (daysCount == 30) {
            if (dayCurrent == 31) {
                $('#day, input[name="date_day"]').val(30);
            }
            $('#day option[value=31]').hide();
        } else if (daysCount == 29) {
            if (dayCurrent > 29) {
                $('#day, input[name="date_day"]').val(29);
            }
            $('#day option[value=31], #day option[value=30]').hide();
        }
    });

    $datepicker.on('input', '#year', function() {
        updateDateFields('year', this.value);
    });

    $datepicker.on('change', 'select', function() {
        updateDateFields($(this).attr('id'), this.value);
    });

    function updateDateFields(period, value) {
        if (period == 'year') {
            $('input[name="date_year"]').val(value);
        } else if (period == 'time' || period == 'time_min') {
            $('input[name="date_time"]').val($('#time').val() + ':' + $('#time_min').val());
            $('input[name="date_time_ampm"]').val($('#time_ampm').val());
        } else {
            $('input[name="date_' + period + '"]').val(value);
        }
    }

    var $form = $('#formEventEditDate');

    $form.on('submit', function(e) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            encode: true,
        }).done(function(response) {
            $.modal.close();
            if (response.loadEvents) {
                loadEvents();
                $.ajax({
                    type: 'PUT',
                    url: '/timelines/' + response.timeline_id + '/reorder',
                    dataType: 'json',
                    encode: true
                });
            }
            //console.log(response.result);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            var errorData = JSON.parse(jqXHR.responseText);
            mapErrorsToForm(errorData.errors);
            //console.log(errorData);
            //console.log(textStatus);
            //console.log(errorThrown);
        }).always(function() {
            // Always run after .done() or .fail()
        });
        e.preventDefault();
    });

    function mapErrorsToForm(errorData) {
        // reset things!
        $form.find('.control__error').remove();
        $form.find(':input').each(function() {
            var fieldName = $(this).attr('name');
            if (!errorData[fieldName]) {
                // no error!
                return;
            }
            var $error = $('<span class="control__error"></span>');
            $error.html(errorData[fieldName]);
            $(this).after($error);
        });
    }

}