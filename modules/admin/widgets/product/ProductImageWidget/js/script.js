var productImage = new ProductImage();
productImage.init();

/** Изображения товаров в админке */
function ProductImage() {
    this.productId = null;
    this.images = {};

    /**
     * Инициализация
     */
    this.init = function () {
        var self = this;
        this.events();
        var elements = $('.product-images__item');
        $(elements).each(function (key, data) {
            var link = $(data).find('img').attr('src');
            self.images[link] = {url: link};
        });
    };

    /**
     * Добавление изображения
     */
    this.add = function (file, product) {
        this.productId = product;
        $('#addImageModal').modal('hide');
        this.images[file.url] = file;
        this.render();
    };

    /**
     * Удаление изображения
     */
    this.remove = function (key, id) {
        var data = {
            id: id
        };

        $.post(
            '/admin/api/product-image/remove-images',
            data,
            function (success) {

            }
        );

        delete this.images[key];
        this.render();
    };

    /**
     * Рендер изображений
     */
    this.render = function () {
        var sendData = [];

        $('.product-images').html("");
        for (var key in this.images) {
            var data = this.images[key];
            var html = '<div class="col-md-2 product-images__item">' +
                '<a href="#" class="thumbnail product-images__link">' +
                '<img class="product-images__image" src="' + data.url + '">' +
                '</a>' +
                '</div>';
            $('.product-images').append(html);
            sendData.push(key);
        }

        this.save(sendData);
    };

    /**
     * Сохранение изображений
     */
    this.save = function (sendData) {
        var data = {
            id: this.productId,
            data: sendData
        };

        $.post(
            '/admin/api/product-image/set-images',
            data,
            function (success) {

            }
        );
    };

    /**
     * События
     */
    this.events = function () {
        var self = this;
        $('body').on('click', '.product-images__link', function () {
            var link = $(this).find('img').attr('src'),
                id = $(this).data('id');
            self.remove(link, id);
            self.render();
        });
    };
}
