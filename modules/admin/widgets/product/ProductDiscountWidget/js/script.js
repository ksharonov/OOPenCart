/**
 * Класс скидок продукта
 * @constructor
 */
function ProductDiscount() {
}

ProductDiscount.prototype = {
    constructor: ProductDiscount
};

ProductDiscount.prototype.init = function () {
    console.log('init');
    this.events();
};

/**
 * Устанавливаем скидку
 */
ProductDiscount.prototype.setDiscount = function () {
    var form = $('#newDiscountForm').serialize();
    $.post(
        '/admin/api/products/set-discount',
        form,
        function (success) {
            $.pjax.reload({container: '#discounts'});
            $('#addDiscountModal').modal('hide');
        }
    )
};

ProductDiscount.prototype.events = function () {
    var self = this;
    $('body').on('click', '._set_discount', function () {
        self.setDiscount();
    });
};

var productDiscount = new ProductDiscount();
productDiscount.init();