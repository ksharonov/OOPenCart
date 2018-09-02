/**
 * Виджет управления заказами
 * @constructor
 */
function ProfileOrdersWidget() {

}

ProfileOrdersWidget.prototype = {
    constructor: ProfileOrdersWidget
};

ProfileOrdersWidget.prototype.checked = [];

/**
 * Инициализация
 */
ProfileOrdersWidget.prototype.init = function () {
    this.events();
};

/**
 * Копирование заказа
 */
ProfileOrdersWidget.prototype.copy = function () {
    if (this.checked.length == 0){
        return false;
    }
    $.post(
        '/api/order/copy',
        {
            id: this.checked
        },
        function (success) {
            console.log(success);
        }
    );
};


/**
 * Отмена заказа
 */
ProfileOrdersWidget.prototype.cancel = function () {
    $.post(
        '/api/order/cancel',
        {
            id: this.checked
        },
        function (success) {

        }
    );
};

/**
 * События
 */
ProfileOrdersWidget.prototype.events = function () {
    var self = this;

    $('body').on('click', '._profile_order_list_item', function () {
        self.checked = [];
        $('._profile_order_list_item:checked').each(function (key, data) {
            var orderId = $(data).data('order-id');
            self.checked.push(orderId);
        });
    });

    $('body').on('click', '._order_copy', function () {
        self.copy();
    });

    $('body').on('click', '._order_cancel', function () {
        self.cancel();
    });

    $('body').on('click', '._order_open', function (event) {

    });

    $('body').on('click', '._order_close', function () {
        self.cancel();
        // var url = $(this).prop('href');
        // window.history.pushState('page2', 'Title', url);
    });
};

var profileOrdersWidget = new ProfileOrdersWidget();
profileOrdersWidget.init();