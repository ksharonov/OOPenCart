var adminProductAttribute = new AdminProductAttributeClass();


/** Виджет связки атрибута с чем-нибудь */
function AdminProductAttributeClass() {

    var data = {
        className: "",
        id: ""
    };

    /**
     * Установка атрибута товара
     */
    this.set = function (productId, attributeId, value) {

        var self = this;

        if (value == undefined) {
            var value = $('[data-key="' + attributeId + '"]').find('[name="value"]').val();
        }

        var data = {
            productId: productId,
            attributeId: attributeId,
            value: value,
            relationData: self.data
        };
        $.post(
            '/admin/api/products/set-attribute',
            data,
            function (success) {
                $.pjax.reload({container: '#attributes'});
                $('#addAttributeModal').modal('hide');
                $('#addAttributeModal input').val("");
            }
        );
    };

    /**
     * Удаление атрибута
     */
    this.delete = function (productId, attributeId) {

        var self = this;

        var data = {
            productId: productId,
            attributeId: attributeId,
            relationData: self.data
        };

        $.post(
            '/admin/api/products/delete-attribute',
            data,
            function (success) {
                $.pjax.reload({container: '#attributes'});
            }
        );
    };

    /**
     * Установка по изменению
     */
    this.inlineSet = function (productId, attributeId) {
        var value = $('[data-attr-id="' + attributeId + '"][data-product-id="' + productId + '"]').val();
        self.set(productId, attributeId, value);
    };

    /**
     * Сохранение
     */
    this.save = function (productId) {
        var self = this;
        $('#attributes [data-product-id="' + productId + '"]').each(function (key, data) {
            var attributeId = $(data).data('attr-id');
            var value = $(data).val();
            self.set(productId, attributeId, value);
        });
    };
}

// /**
//  * Установка атрибута товара
//  */
// function setProductAttribute(productId, attributeId, value) {
//     if (value == undefined) {
//         var value = $('[data-key="' + attributeId + '"]').find('[name="value"]').val();
//     }
//
//     var data = {
//         productId: productId,
//         attributeId: attributeId,
//         value: value
//     };
//     console.log(data);
//     $.post(
//         '/admin/api/products/set-attribute',
//         data,
//         function (success) {
//             $.pjax.reload({container: '#attributes'});
//             $('#addAttributeModal').modal('hide');
//             $('#addAttributeModal input').val("");
//         }
//     );
// }
//
// /**
//  * Удаление атрибута
//  */
// function deleteProductAttribute(productId, attributeId) {
//     var data = {
//         productId: productId,
//         attributeId: attributeId
//     };
//
//     $.post(
//         '/admin/api/products/delete-attribute',
//         data,
//         function (success) {
//             $.pjax.reload({container: '#attributes'});
//         }
//     );
// }
//
// /**
//  * Установка по изменению
//  */
// function inlineSetProductAttribute(productId, attributeId) {
//     var value = $('[data-attr-id="' + attributeId + '"][data-product-id="' + productId + '"]').val();
//     setProductAttribute(productId, attributeId, value);
// }
//
// /**
//  * Сохранение
//  */
// function saveProductAttributes(productId) {
//     $('#attributes [data-product-id="' + productId + '"]').each(function (key, data) {
//         var attributeId = $(data).data('attr-id');
//         var value = $(data).val();
//         setProductAttribute(productId, attributeId, value);
//     });
// }