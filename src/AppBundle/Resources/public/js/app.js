function refreshPage() {
    var parts = window.location.href.split('?');
    var query = '';

    if (parts[1] !== undefined && parts[1].indexOf('header=remove') > -1) {
        query = '?header=remove';
    }

    window.location = parts[0] + query;
}

$('#show-fact').click(function (e) {
    e.preventDefault();

    $('#question').addClass('hidden');
    $('#fact').removeClass('hidden');
});

$('#hide-fact').click(function (e) {
    e.preventDefault();

    $('#question').removeClass('hidden');
    $('#fact').addClass('hidden');
});

$('.card-refresh').click(function (e) {
    refreshPage();
});

$('.card-refresh-mobile').click(function (e) {
    refreshPage();
});