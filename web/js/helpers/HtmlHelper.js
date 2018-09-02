/**
 * Хелпер для HTML
 * @constructor
 */
function HtmlHelper() {

}

HtmlHelper.prototype = {
    constructor: HtmlHelper
};

HtmlHelper.prototype.init = function () {

};


/**
 * Вставка данных в таблицу
 * @param table
 * @param content
 * @param rowOptions
 */
HtmlHelper.prototype.appendRowToTable = function (table, content, rowOptions) {
    var items = [], tbody;

    if (rowOptions === undefined || rowOptions === null) {
        rowOptions = {};
    }


    for (var i = 0; i < content.length; i++) {
        var itemContent = content[i],
            insertedContent;

        if (itemContent instanceof Object) {
            insertedContent = itemContent;
        } else {
            insertedContent = {
                class: 'tbl__td',
                html: itemContent
            };
        }

        items.push(
            $('<td/>', insertedContent)
        );
    }

    rowOptions = $.extend({}, rowOptions, {
        append: items
    });

    tbody = $(table).find('tbody');
    tbody.append($('<tr/>', rowOptions));
};

htmlHelper = new HtmlHelper();
htmlHelper.init();