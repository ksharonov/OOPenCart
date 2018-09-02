var activeAttributeType;
var attributeValues;

var attributeTypesView = [
    {
        'name': 'Флаг: один',
        'generate': generateOne
    },
    {
        'name': 'Флаг: несколько',
        'generate': generateMany
    },
    {
        'name': 'Выбор: текст',
        'generate': generateMany
    },
    {
        'name': 'Выбор: число',
        'generate': generateMany
    },
    {
        'name': 'Число',
        'generate': generateOne
    },
    {
        'name': 'Текст',
        'generate': generateOne
    }
];

$(document).ready(function () {
    activeAttributeType = $('#productattribute-attributegroupid').val() || null;
    attributeValues = $('#productattribute-params').val() || null;

    if (activeAttributeType != null) {
        init();
    }

    $('#productattribute-type').change(function () {
        activeAttributeType = $(this).val();
        init();
    });

    $('.attribute-input-add').click(function () {
        var newInput = generateInput();
        $('.attribute-inputs').append(newInput);
    });

    $('[type="submit"]').click(function (event) {
        var string = generateParams();
        $('#productattribute-params').val(string);
    });
});

function init() {
    var html = attributeTypesView[activeAttributeType].generate();
    if (attributeValues) {
        var json = JSON.parse(attributeValues);
        html = "";
        json.forEach(function (data, key) {
            html += generateInput(data);
        });
    }
    $('.attribute-inputs').html(html);
}

function generateOne() {
    $('.attribute-input-add').hide();
    $('.field-productattribute-inputs').hide();
    return "";
}

function generateMany() {
    var element = generateInput();
    $('.field-productattribute-inputs').show();
    $('.attribute-input-add').show();
    return element;
}

function generateBase() {
    var input = generateInput();
    // var element = $('<div class="input-group">' +
    //     input +
    //     '<span class="input-group-addon"></span>' +
    //     '</div>'
    // );
    var element = input;
    return element;
}

function generateInput(value) {
    if (value == undefined) {
        value = "";
    }
    var element = '<input type="text" value="' + value + '" class="form-control" placeholder="Название поля"><br>';
    return element;
}

function generateParams() {
    var values = [];
    var inputs = $('.attribute-inputs input');
    inputs.each(function (key, data) {
        values.push($(data).val());
    });
    return JSON.stringify(values);

}