function getManufacturers(query) {
    var data = {
        search: query.term
    };

    $.ajax({
        url: '/admin/api/product-manufacturers/get-by-name',
        data: data,
        method: 'GET',
        success: function (response) {
            var filters = JSON.parse(response);
            var dataSelect2 = {results: []};
            $('#manufacturer').html("");
            $(filters).each(function (key, data) {
                dataSelect2.results.push({id: data.id, text: data.title});
                $('#manufacturer').append('<option value="' + data.id + '">' + data.title + '</option>');
            });

            query.callback(dataSelect2);

        },
        error: function (response) {
            console.error(response);
        }
    });
}

$(document).on('click', '#preProductSubmit', function () {
    $('#productSubmit').click();
});

$(document).on('click', '#preProductCancel', function () {
    $('#productCancel').click();
});