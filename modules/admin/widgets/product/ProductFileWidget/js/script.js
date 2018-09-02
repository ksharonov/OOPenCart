var productFile = new ProductFile();
productFile.init();
/** Изображения товаров в админке */
function ProductFile() {
    this.productId = null;
    this.relModel = null;

    /**
     * Инициализация
     */
    this.init = function () {
        this.events();
    };

    /**
     * Добавление изображения
     */
    this.add = function (file, product, relModel) {
        this.productId = product;
        this.relModel = relModel;
        $('#addFileModal').modal('hide');
        this.save(file);
    };

    /**
     * Удаление файлов
     */
    this.remove = function (id) {
        $.post(
            '/admin/api/product-files/delete',
            {
                id: id
            },
            function (success) {
                productFile.reload();
            }
        );
    };

    /**
     * Сохранение файлов
     */
    this.save = function (file) {
        var type = $('[name="product-file-type"]').val(),
            link = $('[name="link"]').val() || null;

        var data = {
            productId: this.productId,
            relModel: this.relModel,
            path: file.url,
            type: type,
            link: link
        };
        $.post(
            '/admin/api/product-files/set',
            data,
            function (success) {
                productFile.reload();
            }
        );
    };

    this.reload = function () {
        pjax.reload(['#files']);
    };

    /**
     * События
     */
    this.events = function () {
        var self = this;
        $('body').on('click', '._to_editor', function (event) {
            event.preventDefault();
            var imageUrl = $(this).prop('href');
            console.log(imageUrl);
            self.imageToEditor(imageUrl);
        });
    };

    this.imageToEditor = function (imageUrl) {
        var ed = tinyMCE.get()[0] || false;
        if (ed) {
            var range = ed.selection.getRng();
            var newNode = ed.getDoc().createElement("img");
            newNode.src = imageUrl;
            range.insertNode(newNode);
        }
    }
}
