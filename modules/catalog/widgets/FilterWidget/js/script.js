var productSearch = new ProductSearch();
productSearch.init();

function ProductSearch() {
    this.attributes = {};
    this.reloadFilters = false;

    this.init = function () {
        this.events();
        this.initFilter();
    };

    /**
     * Задание событий
     */
    this.events = function () {
        var self = this;
        $('[data-filter-group="checkbox"]').on('change', function (event) {
            var attrId = $(this).data('attribute-id'),
                val = $(this).val(),
                inArray = $.inArray(val, self.attributes[attrId].selects);

            if (inArray == -1) {
                self.attributes[attrId].selects.push(val);
            } else {
                self.attributes[attrId].selects.splice(inArray, 1);
            }
        });

        $('body').on('click', '._filter_submit, ._sort_submit', function (event) {
            $('._fast_filter_submit').removeClass('sort__fast_active');
            self.reloadFilters = false;
            self.onFilterLoad();
        });

        $('body').on('click', '[for="check-city-all"]', function () {
            var element = $('#check-city-all'),
                checked = element.prop('checked');
            if (!checked) {
                $('[name="ProductSearch[cityFilter][]"]').prop('checked', true);
            } else {
                $('[name="ProductSearch[cityFilter][]"]').prop('checked', false);
            }
        });
    };

    /**
     * Инициализация фильтра
     */
    this.initFilter = function () {
        var elements = $('[data-attribute-id]'),
            self = this;

        $.each(elements, function (key, data) {
            var attrid = $(data).data('attribute-id');

            self.attributes[attrid] = {
                id: attrid,
                selects: [null]
            };
        });
    };

    /**
     * Действия после нажатия кнопки быстрого фильтра или подтверждения фильтрации
     */
    this.onFilterLoad = function () {
        var category = $('[name="ProductSearch[category]"]').val(),
            productList = $('#product-list'),
            mode = productList.data('mode'),
            title = productList.data('title'),
            serializeFormArray, requestUrl, self = this,
            pathName = window.location.pathname;

        serializeFormArray = $('.filter').serializeArray();

        if (this.reloadFilters) {
            requestUrl = urlHelper.build();
        } else {
            requestUrl = urlHelper.joinWithSerializeArray(serializeFormArray);
        }

        history.pushState(null, null, pathName + requestUrl);

        $.post(
            '/api/catalog/index' + requestUrl,
            {
                filterSelect: self.attributes,
                category: category,
                mode: mode,
                title: title
            },
            function (success) {
                var html = success.html,
                    min = 0,
                    max = 500000;
                html = html.split('/api/').join('/');

                $.when($('._filter_data').html(html))
                    .then(function () {
                        min = productList.data('min-price');
                        max = productList.data('max-price');
                        reloadPriceRange(min, max);
                    });

                $.each(success.filterSelect, function (key, val) {
                    $.each(val, function (filterValue, filterCount) {
                        //todo остаток реализован частично
                        // $('[data-attribute-id="' + key + '"] [data-filter-value="' + filterValue + '"]').html('(' + filterCount + ')');
                    });
                });

                $('body').find('.catalog-product__image, .catalog-product_listview__image').Lazy({
                    afterLoad: function (element) {
                        $(element).parent().removeClass('catalog-product_listview__image-wrap_loading')
                            .removeClass('catalog-product__image-wrap_loading');
                    }
                });
            }
        );

    };
}
