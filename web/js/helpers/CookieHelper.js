/**
 * Класс-помощник для Cookie
 * @constructor
 */
function Cookie() {

}

Cookie.prototype = {
    constructor: Cookie
};

/**
 * Получить значение
 * @param name
 * @param json
 * @returns {*}
 */
Cookie.prototype.get = function (name, json) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));

    var result = matches ? decodeURIComponent(matches[1]) : undefined;
    console.log(name, json);
    if (json && result !== undefined) {
        try {
            result = JSON.parse(result);
        } catch (err) {
            result = {};
        }
    }

    if (json && (result == 'null' || result == null)) {
        result = {};
    }

    return result;
};

/**
 * Установить значение
 * @param name
 * @param value
 * @param time
 * @param path
 */
Cookie.prototype.set = function (name, value, time, path) {
    var date = new Date();

    date.setTime(date.getTime() + (time * 1000));
    // document.cookie = `${name}` + '=' + `${value}` +
    //     '; path=' + `${path}` +
    //     '; expires=' + date.toUTCString();

    document.cookie = name + '=' + value + '; ' + 'path=' + path + '; ' + 'expires=' + date.toUTCString();
};

var cookie = new Cookie();