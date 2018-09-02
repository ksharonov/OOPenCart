var inlineSelect = new InlineSelectWidget();
inlineSelect.init();

/**
 * Класс инлайнового селектора в виде кнопок
 * @constructor
 */
function InlineSelectWidget() {
    /**
     * Инициализация виджета
     */
    this.init = function () {
        this.events();
    };

    /**
     * Генерируем инпуты
     * @param {integer[]} result
     * @param {string} relation
     */
    this.generateInputs = function (result, relation) {
        $('div[data-inline-select-relation="' + relation + '"]').html('');

        var jsonResult = JSON.stringify(result);

        $('input[name="' + relation + '"]').val(jsonResult);

        for (var i = 0; i < result.length; i++) {
            var value = result[i];

            $('<input name="' + relation + '[]">', {
                'hidden': true,
                'class': 'row dynamic-params__item',
                'data-param': ''
            })
                .val(value)
                .appendTo('div[data-inline-select-relation="' + relation + '"]');
        }

        if (result.length === 0) {
            $('<input name="' + relation + '[]">', {
                'hidden': true,
                'class': 'row dynamic-params__item',
                'data-param': ''
            })
                .val(null)
                .appendTo('div[data-inline-select-relation="' + relation + '"]');
        }
    };

    /**
     * События виджета
     */
    this.events = function () {
        var self = this;

        $('body').on('click', 'button[data-inline-select]', function (event) {
            var id = $(this).data('id'),
                elementInput = $('input[data-inline-select][data-id="' + id + '"]'),
                val = $(elementInput).val();

            if (val === "") {
                val = 1;
            } else {
                val = "";
            }

            $(elementInput).val(val);

            self.onUpdate(this);

        });
    };

    /**
     * Действие обновления
     * @param {jQuery} element
     */
    this.onUpdate = function (element) {
        var self = this,
            element = $('input[data-inline-select]'),
            result = [];

        $.each(element, function (key, data) {
            var value = $(data).val(),
                id = $(data).data('id');

            if (value) {
                result.push(id);
            }
        });

        var relation = $(element).data('inline-select-relation');


        var formName = $($('[data-inline-select-params][data-inline-select-relation="' + relation + '"]')[0])
            .data('form-name');

        $('#' + formName).submit();

        self.generateInputs(result, relation);
    };
}