/* THIS GETS A VIEW:
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: '/timelines/' + $('meta[name="timeline"]').attr('content') + '/settings/edit',
    type: 'GET',
    success: function(data) {
        $('#timelineSettings').html(data);
    }
});*/

// detect settings change
$('#timelineSettings').on('input', ':input', function(e) {
    var $settings = $(e.delegateTarget);
    $settings.find(':input').each(function() {
        var $this = $(this);
        if ($this.val() === $this.data('value')) {
            return;
        }
        $('#timelineSettings>button').prop("disabled", false);
        return false;
    });
});