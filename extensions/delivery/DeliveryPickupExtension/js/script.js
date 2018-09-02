function DeliveryPickupExtension() {

}

DeliveryPickupExtension.prototype = {
    constructor: DeliveryPickupExtension
};

DeliveryPickupExtension.prototype.init = function () {
    this.events();
};

DeliveryPickupExtension.prototype.events = function () {
    var self = this,
        activeStorage = $('[name^="OrderSession[deliveryData]"]').val();
    self.checkDate(activeStorage);

    $(document).on('change', '[name^="OrderSession[deliveryData]"]', function () {
        var storageId = $(this).val();
        self.checkDate(storageId);
    });
};

DeliveryPickupExtension.prototype.checkDate = function (storageId) {
    $.get(
        '/api/delivery/get-date',
        {
            data: {
                storageId: storageId
            }
        },
        function (success) {
            if (success) {
                $('#deliveryDateFrom').html(success);
            }
        }
    );

};

var deliveryPickupExtension = new DeliveryPickupExtension();
deliveryPickupExtension.init();