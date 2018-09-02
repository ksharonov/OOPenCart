function editProductOptionParam(productModelId, productOptionParamId = null) {
    var data = {};
    if(productOptionParamId) {
        data.productOptionParamId = productOptionParamId;
    }
    data.productModelId = productModelId;

    $.ajax({
        url: '/admin/api/products/option-param-popup',
        data: data,
        method: 'POST',
        dataType: 'HTML',
        success: function (response) {
            $('#addOptionParamModalContent').html(response);
        },
        error: function (response) {
            alert('Request was not handled properly');
            console.error(response);
        }
    });
}

function deleteProductOptionParam(productOptionParamId) {
    var data = {};
    data.productOptionParamId = productOptionParamId;
    if(confirm('Вы уверены, что хотите удалить этот набор опций?')) {
        $.ajax({
            url: '/admin/api/products/delete-option-param',
            data: data,
            method: 'POST',
            dataType: 'HTML',
            success: function () {
                $.pjax.reload({container: '#product-option-param'});
            },
            error: function (response) {
                alert('Request was not handled properly');
                console.error(response);
            }
        });
    }
}