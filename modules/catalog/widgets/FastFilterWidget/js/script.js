var fastFilter = new FastFilter();
fastFilter.init();

/**
 * Быстрая фильтрация
 * */
function FastFilter() {

    /*
     * Инициализация виджета
     */
    this.init = function () {
        this.events();
    };

    /*
     * События
     */
    this.events = function () {
        var self = this;
        $('body').on('change', '.sort__select', function (event) {
            var value = $(this).val();
            urlHelper.set('sort', value);
            productSearch.onFilterLoad();
        });

        $('body').on('click', '._fast_filter_submit', function (event) {
            event.preventDefault();
            var params = $(this).data('link').split('&');
            $('._fast_filter_submit').not(this).removeClass('sort__fast_active');

            if ($(this).hasClass('sort__fast_active')) {
                for (var i = 0; i < params.length; i++) {
                    var param = params[i].split('=');

                    if (param[0] == 'category') {
                        continue;
                    }

                    urlHelper.remove(param[0]);
                }
            } else {
                for (var i = 0; i < params.length; i++) {
                    var param = params[i].split('=');
                    urlHelper.set(param[0], param[1]);
                }
            }

            $(this).toggleClass('sort__fast_active');
            self.setFilter();
            productSearch.reloadFilters = true;
            productSearch.onFilterLoad();
        });
    };

    this.setFilter = function () {
        $.each(urlHelper.params, function (key, val) {
            var element = $('[name*="' + urlHelper.formName + '[' + key + ']"]');
            console.log(element, val);
            console.log('[name*="' + urlHelper.formName + '[' + key + ']"]');
        });
    };
}