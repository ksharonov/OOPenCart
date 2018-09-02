var orderProcess = new OrderProcessClass();

function OrderProcessClass() {
    this.clientTypeUser = 0;
    this.clientTypeClient = 1;

    this.constructor = function () {
        this.events();
    };

    /**
     * Отслеживание событий
     */
    this.events = function () {
        var self = this;
        $('body').on('click', '[type="submit"]', function (event) {
            self.clientTypeError(event)
        });
        $('#orderProcess').on('submit', function (event) {
            self.clientTypeError(event)
        });
    };

    this.clientTypeError = function () {
        var self = this,
            clientType = $('[name="OrderSession[clientType]"]:checked').val();

        if (clientType == self.clientTypeClient) {
            modal.show('#clientTypeError');
            $('button.checkout__forward[type="submit"]').prop('disable', true);
            event.preventDefault();
        }
    };

    this.constructor();
}


