var adminProductUnit = new AdminUnitClass();
adminProductUnit.init();

/** Отображение ЕИ в товаре */
function AdminUnitClass() {

    /**
     * Инициализация
     */
    this.init = function () {
        this.events();
    };

    /**
     * Установка ЕИ для продукта
     */
    this.set = function (productId, unitId, rate) {
        var data = {
            productId: productId,
            unitId: unitId,
            rate: rate
        };

        if (rate == undefined) {
            delete data['rate'];
        }


        this.request({
            data: data,
            type: 'set'
        });
    };

    /**
     * Удаление ЕИ в продукте
     */
    this.delete = function (productUnitId) {
        var data = {
            id: productUnitId
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
            '/admin/api/product-units/' + obj.type,
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
        var self = this;
        $('body').on('change', '.product-unit__value-item', function () {
            var element = $(this);
            var productId = $(element).data('product-id'),
                unitId = $(element).data('unit-id'),
                value = $(element).val();
            self.set(productId, unitId, value);
        });
    };

    /**
     * Финишная функция после запроса
     */
    this.success = function () {
        $.pjax.reload({container: '#units'});
        $('#addUnitModal').modal('hide');
        $('#addUnitModal input').val("");
    };
}