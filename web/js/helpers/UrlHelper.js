/**
 * Класс для составления URL для фильтрации и сортировки продуктов
 * @constructor
 */
function UrlHelper() {

}

UrlHelper.prototype = {
    constructor: UrlHelper
};

UrlHelper.prototype.currentUrl = '';
UrlHelper.prototype.formName = 'ProductSearch';

UrlHelper.prototype.params = {};

UrlHelper.prototype.preparedParams = {};

/**
 * Инициализация
 */
UrlHelper.prototype.init = function () {
    console.info('Website ' + window.location.href + ' ready');
};

/**
 * Установить ключ
 * @param key
 * @param value
 */
UrlHelper.prototype.set = function (key, value) {
    this.params[key] = value;
};

/**
 *
 * Удалить значение
 * @param key
 */
UrlHelper.prototype.remove = function (key) {
    delete this.params[key];
    // this.params.splice(key, 1);
};

/**
 * Получить значение
 * @param key
 * @returns {*}
 */
UrlHelper.prototype.get = function (key) {
    return this.params[key];
};

/**
 * Собрать URL
 * @returns {{}}
 */
UrlHelper.prototype.build = function () {
    var params, stringRequest;
    params = this.preBuild();
    stringRequest = this.stringify(params);
    return stringRequest;
};

/**
 *
 * @param params должен быть объектом, который возвращает serializeArray
 */
UrlHelper.prototype.joinWithSerializeArray = function (params) {
    var buildString, preArray = [], preString = '';
    buildString = this.build();
    $.each(params, function (key, element) {
        preArray.push(element.name + '=' + element.value);
    });
    preString += preArray.join('&');

    if (buildString === null) {
        return '?' + preString;
    } else {
        return buildString + '&' + preString;
    }

};

/**
 * Подготовка
 * @returns {{}}
 */
UrlHelper.prototype.preBuild = function () {
    var preParams = {},
        self = this;

    $.each(this.params, function (key, data) {
        preParams[self.formName + '[' + key + ']'] = data;
    });

    return preParams;
};

/**
 * Получаем строку запроса
 * @param params
 */
UrlHelper.prototype.stringify = function (params) {
    var preString = '?', preArray = [];
    $.each(params, function (key, data) {
        try {
            data = JSON.parse(data);
        }
        catch (e) {
            console.log(data);
        }


        if (Array.isArray(data)) {
            data.forEach(function (elementData, elementKey) {
                preArray.push(key + '[]=' + elementData);
                // preString += key + '[]=' + elementData + '&';
            });
        } else {
            preArray.push(key + '=' + data);
            // preString += key + '=' + data + '&';
        }
    });

    if (preArray.length > 0) {
        preString += preArray.join('&');
    } else {
        preString = null;
    }
    return preString;
};

/**
 * Замена параметра
 * @param url
 * @param paramName
 * @param paramValue
 * @returns {*}
 */
UrlHelper.prototype.replaceParam = function (url, paramName, paramValue) {
    var pattern = new RegExp('(\\?|\\&)(' + paramName + '=).*?(&|$)');
    var newUrl = url;
    if (url.search(pattern) >= 0) {
        newUrl = url.replace(pattern, '$1$2' + paramValue + '$3');
    }
    else {
        newUrl = newUrl + (newUrl.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue
    }
    return newUrl
};

var urlHelper = new UrlHelper();
urlHelper.init();
urlHelper.build();