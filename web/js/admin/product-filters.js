setTimeout(function () {
    if (typeof $.fn.select2 == 'function') {
        $("#source").select2({
            minimumInputLength: 1,
            query: function (query) {
                var sourceType = $('#productfilter-source').val();
                getFiltersBySource(sourceType, query);

            }
        });
    }
}, 1000);

function getFiltersBySource(sourceType, query) {
    var data = {
        source: sourceType,
        search: query.term
    };
    $.ajax({
        url: '/admin/api/product-filters/get-by-source',
        data: data,
        method: 'GET',
        success: function (response) {
            var filters = JSON.parse(response);
            var dataSelect2 = {results: []};

            $('#source').html("");

            var key = 0;

            $.each(filters, function (keyGroup, dataGroup) {
                $('#source').append('<optgroup label="' + keyGroup + '"></option>');

                dataSelect2.results.push({
                    'text': keyGroup,
                    'children': []
                });

                $(dataGroup).each(function (key, data) {

                    dataSelect2.results[key].children.push({id: data.id, text: data.title});
                    $('optgroup[label="' + keyGroup + '"]').append('<option value="' + data.id + '">' + data.title + '</option>');
                });

                key++;
            });

            query.callback(dataSelect2);

        },
        error: function (response) {
            console.error(response);
        }
    });
}