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
    window.location = window.location.href.split("?")[0];
});

$('.card-refresh-mobile').click(function (e) {
    window.location = window.location.href.split("?")[0];
});