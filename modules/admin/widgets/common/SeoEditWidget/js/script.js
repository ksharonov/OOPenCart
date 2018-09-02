$("#seo-form").wrapInner("<form id='seo-form'/>").children(0).unwrap();

$('#seo').on('keyup', 'input', function () {
    var form = $('#seo-form').serialize();

    $.get(
        '/admin/api/common/seo/set?' + form,
        function (success) {

        }
    );

});