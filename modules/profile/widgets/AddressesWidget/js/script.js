/**
 * Виджет управления страницей адресами
 * @constructor
 */
function ProfileAddressWidget() {

}

ProfileAddressWidget.prototype = {
    constructor: ProfileAddressWidget
};

ProfileAddressWidget.prototype.init = function () {
    this.events();
};

ProfileAddressWidget.prototype.events = function () {
    $('body').on('submit', '#addressForm', function (event) {
        var formData = $('#addressForm').serialize();

        event.preventDefault();

        $.post(
            '/api/address/index',
            formData,
            function (success, error) {
                if (success) {
                    htmlHelper.appendRowToTable($('.address__table'), [
                        success.country,
                        success.city,
                        success.postcode,
                        success.address
                    ]);
                    modal.hide('#createAddressModal');
                }
            }
        );

    });

    $('._client_address_delete').on('click', function () {
        var id = $(this).data('address-id');
        $.ajax({
            url: '/api/address/index',
            type: "DELETE",
            data: {
                id: id
            },
            success: function (success) {
                if (success) {
                    $('tr[data-address-id="' + id + '"]').remove();
                }
            }
        });
    });

    $('.access__add').on('click', function () {
        $('#addressForm').find('[name="id"]').val('0');
    });

    $('._client_address_edit').on('click', function (event) {
        var addressId = $(this).data('address-id'),
            data = $('tr[data-address-id="' + addressId + '"]').find('td[data-name]'),
            obj = {};
        $.each(data, function (key, data) {
            var name = $(data).data('name'),
                val = $(data).html().trim();
            obj[name] = val;
            $('#addressForm').find('[name="' + name + '"]').val(val);
        });

        $('#addressForm').find('[name="id"]').val(addressId);


    });
};

var profileAddressWidget = new ProfileAddressWidget();
profileAddressWidget.init();