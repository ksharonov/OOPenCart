$('._change_product_list_view').on('click', function (event) {
    var productWidgetId = $(this).data('product-list-id');

    $('[data-view="list"]').toggle();
    $('[data-view="card"]').toggle();

    $('.view__item_grid').toggleClass('view__item_active');
    $('.view__item_list').toggleClass('view__item_active');

    var mode = $('[data-view="list"]').is(":visible") ? 'list' : 'card';

    //todo пересмотреть чуть позже
    cookie.set('catalogView', mode, 360000 * 24, '/');
    cookie.set('catalogViewWidgetId', productWidgetId, 360000 * 24, '/');
});