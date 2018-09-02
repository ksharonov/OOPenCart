function updateOptionValue(optionValueId, newValue) {
    var data = {};
    data.optionValueId = optionValueId;
    data.newValue = newValue;

    $.ajax({
        url: '/admin/api/product-option-values/update-option-value',
        data: data,
        method: 'POST',
        success: function (response) {
            $.pjax.reload({container: '#product-option-values'});
        },
        error: function (response) {
            alert('Request was not handled properly');
            console.error(response);
        }
    });
}

function deleteOptionValue(optionValueId) {
    var data = {};
    data.optionValueId = optionValueId;

    $.ajax({
        url: '/admin/api/product-option-values/delete-option-value',
        data: data,
        method: 'POST',
        success: function (response) {
            $.pjax.reload({container: '#product-option-values'});
        },
        error: function (response) {
            alert('Request was not handled properly');
            console.error(response);
        }
    });
}

function createOptionValue(optionId, newValue) {
    var data = {};
    data.optionId = optionId;
    data.newValue = newValue;

    $.ajax({
        url: '/admin/api/product-option-values/create-option-value',
        data: data,
        method: 'POST',
        success: function (response) {
            $.pjax.reload({container: '#product-option-values'});
            $('#addOptionValueModal').modal('hide');
        },
        error: function (response) {
            alert('Request was not handled properly');
            console.error(response);
        }
    });
}

function delegateOptionValueCreate(form) {
    var values = $(form).serializeArray().reduce(function(obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});
    createOptionValue(values.optionId, values.newValue);
    $(form).find('input[type=text]').val('');
    return false;
}
