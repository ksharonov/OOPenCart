dynamicParam = new DynamicParamWidget();

function DynamicParamWidget() {

    this.add = function () {
        $('<div>', {
            'class': 'row dynamic-params__item',
            'data-param': '',
            'append': ($('<div>', {
                'class': 'col-md-6',
                'append': $('<div>', {
                    'class': 'form-group',
                    'append': $('<input>', {
                        'class': 'form-control dynamic-params__item-key',
                        'name': 'ParamsKey',
                        'placeholder': 'Параметр'
                    })
                })
            }))
                .add($('<div>', {
                    'class': 'col-md-6',
                    'append': $('<div>', {
                        'class': 'form-group',
                        'append': $('<input>', {
                            'class': 'form-control dynamic-params__item-key',
                            'name': 'ParamsValue',
                            'placeholder': 'Значение'
                        })
                    })
                })),
        })
            .appendTo('.dynamic-params');

    };

}

var hiddenParam = $('.hidden-param');

$('body').on('keyup', '[name="ParamsKey"], [name="ParamsValue"]', function () {
    var params = $('[data-param]');
    var obj = {};
    $.each(params, function (key, data) {
        var key = $(data).find('[name="ParamsKey"]').val();
        var value = $(data).find('[name="ParamsValue"]').val();
        if (key != '')
            obj[key] = value;
        hiddenParam.val(JSON.stringify(obj));
    });
});