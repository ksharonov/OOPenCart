$('body').on('click', '[data-sort]', function (event) {
    var dataSort = $(this).data('sort'),
        sortCol = $(this).data('sort-col');

    if (dataSort === '-') {
        $(this).data('sort', null);
        $(this).data('link', 'sort=' + sortCol);
    } else {
        $(this).data('sort', '-');
        $(this).data('link', 'sort=-' + sortCol)
    }
    // if (dataSort === '-') {
    //     $(this).data('sort', '');
    //     $(this).attr('data-sort', '');
    //     $(this).attr('data-link', 'sort=' + sortCol);
    // } else {
    //     $(this).data('sort', '-');
    //     $(this).attr('data-sort', '-');
    //     $(this).data('link', 'sort=-' + sortCol)
    //     $(this).attr('data-link', 'sort=-' + sortCol);
    // }
});