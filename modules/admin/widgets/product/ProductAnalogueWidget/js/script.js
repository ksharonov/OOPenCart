/**
 * Установка аналога для товара
 */
function setProductAnalogue(productId, analogueId) {

    var data = {
        productId: productId,
        analogueId: analogueId
    };

    $.post(
        '/admin/api/products/set-analogue',
        data,
        function (success) {
            $.pjax.reload({container: '#analogues'});
            $('#addAnalogueModal').modal('hide');
        }
    );
}

/**
 * Переключение аналога товара
 */
function toggleAnalogue(id) {
    var data = {
        id: id
    };

    $.post(
        '/admin/api/products/toggle-analogue',
        data,
        function (success) {
            $.pjax.reload({container: '#analogues'});
            $('#addAnalogueModal').modal('hide');
        }
    );
}