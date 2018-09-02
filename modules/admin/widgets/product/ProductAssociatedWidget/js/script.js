var adminProductAssoc = new AdminProductAssocClass();
adminProductAssoc.init();

/** Отображение ЕИ в товаре */
function AdminProductAssocClass() {

    /**
     * Инициализация
     */
    this.init = function () {
        this.events();
    };

    /**
     * Установка сопутствующего для продукта
     */
    this.set = function (productId, productAssociatedId) {
        var data = {
            productId: productId,
            productAssociatedId: productAssociatedId
        };

        this.request({
            data: data,
            type: 'set'
        });
    };

    /**
     * Удаление ЕИ в продукте
     */
    this.delete = function (assocId) {
        var data = {
            id: assocId
        };

        this.request({
            data: data,
            type: 'delete'
        });
    };

    /**
     * Запрос
     */
    this.request = function (obj) {
        var self = this;
        $.post(
            '/admin/api/product-assoc/' + obj.type,
            obj.data,
            function (success) {
                self.success();
            }
        );
    };

    /**
     * События
     */
    this.events = function () {
    };

    /**
     * Финишная функция после запроса
     */
    this.success = function () {
        $.pjax.reload({container: '#assocs'});
        $('#addAssocModal').modal('hide');
    };
}