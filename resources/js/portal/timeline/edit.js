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





// header tabs

if ($(window.location.hash).length) {
    openTab(window.location.hash);
} else {
    openTab('#' + $('section.edit__tab:first').attr('id'));
}

$('.edit__section>ul a, .edit__section a.tab').on('click', function() {
    openTab($(this).attr('href'));
});

function openTab(id) {
    $('.edit__section>ul a').removeClass('active');
    $('.edit__section>ul a[href="' + id + '"]').addClass('active');
    $('section.edit__tab').hide();
    var activeTab = $(id);
    $(activeTab).fadeIn();
}