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

var topHeight = getTopHeight();
setLayout();

$(window).on('resize', function() {
    topHeight = getTopHeight();
    setLayout();
});

// header tabs

if ($(window.location.hash).length) {
    openTab(window.location.hash);
} else {
    openTab('#' + $('section.edit__tab:first').attr('id'));
}

$('.edit__section header>ul a, .edit__section a.tab').on('click', function() {
    openTab($(this).attr('href'));
});

// visibility
$('.visibility').on('click', function(e) {
    e.preventDefault();
    $('.visibility-options').show();
});

$('.visibility-options>span>a').on('click', function(e) {
    e.preventDefault();
    $('.visibility-options').hide();
});

function openTab(id) {
    $('.edit__section header>ul a').removeClass('active');
    $('.edit__section header>ul a[href="' + id + '"]').addClass('active');
    $('section.edit__tab').hide();
    var activeTab = $(id);
    $(activeTab).fadeIn();
}

function getTopHeight() {
    if (screenSize > 4) {
        return $('#topbar').outerHeight() + $('header').outerHeight();
    } else {
        return $('header').outerHeight();
    }
}

function setLayout() {
    if (screenSize > 4) {
        $('.edit__section>div>section').css({
            'height': 'calc(100vh - ' + topHeight + 'px)'
        });
        $('.edit__events').appendTo($('.edit__section'));
    } else {
        $('.edit__section>div>section').css({
            'height': 'auto'
        });
        $('.edit__events').appendTo($('.edit__section>div>section'));
    }
}