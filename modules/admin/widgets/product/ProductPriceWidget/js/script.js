function setProductPrice(productId, productPriceGroupId) {
    var data = {
        productId: productId,
        productPriceGroupId: productPriceGroupId
    };

    $.post(
        "/admin/api/products/set-price",
        data,
        function (success) {
            $.pjax.reload({container: '#prices'});
            $('#addPriceModal').modal('hide');
        }
    );
}

function deleteProductPrice(productPriceId) {
    var data = {
        id: productPriceId
    };

    $.post(
        "/admin/api/products/delete-price",
        data,
        function (success) {
            $.pjax.reload({container: '#prices'});
        }
    );
}

$('body').on('keyup, keydown', '.product-attributes__value', function () {
    var productPriceId = $(this).data('product-price-id');
    inlineSetProductPrice(productPriceId, false)
});

function inlineSetProductPrice(productPriceId, onUpdate) {
    if (onUpdate === undefined) {
        onUpdate = true;
    }

    var value = $('input[data-product-price-id="' + productPriceId + '"]').val();

    var data = {
        productPriceId: productPriceId,
        value: value
    };

    $.post(
        "/admin/api/products/update-price",
        data,
        function (success) {
            if (onUpdate) {
                $.pjax.reload({container: '#prices'});
            }
            $('#addPriceModal').modal('hide');
        }
    );
}