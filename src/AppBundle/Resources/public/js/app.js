function refreshPage(locator) {
    var parts = window.location.href.split('?');
    var query = '';

    if (parts[1] !== undefined && parts[1].indexOf('header=remove') > -1) {
        query = '?header=remove';
    }

    if (locator !== undefined) {
        if (query === '') {
            query = '?'
        } else {
            query = query + '&';
        }

        query = query + 'l=' + locator;
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

$('.show-header').click(function (e) {
    $(this).addClass('hidden');
    $('.show-select').removeClass('hidden');
    $('.show-select select').focus();
});

$('.show-select select').blur(function (e) {
    $('.show-select').addClass('hidden');
    $('.show-header').removeClass('hidden');
}).change(function (e) {
    refreshPage($(this).val());
});

$('.card-refresh').click(function (e) {
    refreshPage();
});

$('.card-refresh-mobile').click(function (e) {
    refreshPage();
});