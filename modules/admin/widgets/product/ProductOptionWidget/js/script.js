function addProductOption(productId, optionId) {
    var data = {};
    data.productId = productId;
    data.optionId = optionId;

    $.ajax({
        url: '/admin/api/products/add-option-to-product',
        data: data,
        method: 'POST',
        success: function (response) {
            $.pjax.reload({container: '#product-option-selector'});
        },
        error: function (response) {
            alert('Request was not handled properly');
            console.error(response);
        }
    });
}

function updateProductOptionValue(productId, optionValueId, element) {
    var data = {};
    data.productId = productId;
    data.optionValueId = optionValueId;

    var url;
    if(element.checked) {
        url = '/admin/api/products/add-product-option-value-to-product';
    } else {
        url = '/admin/api/products/delete-product-option-value-to-product';
    }

    $.ajax({
        url: url,
        data: data,
        method: 'POST',
        success: function (response) {
            $.pjax.reload({container: '#product-option-selector'});
        },
        error: function (response) {
            alert('Request was not handled properly');
            console.error(response);
        }
    });
}