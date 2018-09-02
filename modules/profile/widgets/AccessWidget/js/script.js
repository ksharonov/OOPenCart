/**
 * Виджет управления страницей доступов
 * @constructor
 */
function ProfileAccessWidget() {

}


ProfileAccessWidget.prototype = {
    constructor: ProfileAccessWidget
};

ProfileAccessWidget.prototype.init = function () {
    this.events();
};

ProfileAccessWidget.prototype.events = function () {
    $('#userForm').submit(function (event) {
        var formData = $('#userForm').serialize();

        event.preventDefault();

        $.post(
            '/api/user/index',
            formData,
            function (success) {
                modal.hide('#createAccessModal');
                window.location.reload(false);
            }
        );
    });

    $('.access__add').on('click', function () {
        $('#userForm').find('[name="id"]').val('0');
    });

    $('._client_user_edit').on('click', function (event) {
        var userId = $(this).data('user-id'),
            data = $('tr[data-user-id="' + userId + '"]').find('td[data-name]'),
            obj = {};

        $.each(data, function (key, data) {
            var name = $(data).data('name'),
                val = $(data).html().trim();
            obj[name] = val;
            console.log(name, val);
            $('#userForm [name="' + name + '"]').val(val);
            // console.log($('#userForm [name="' + name + '"]').find(''));
        });

        $('#userForm').find('[name="id"]').val(userId);


    });

    $('._client_user_delete').on('click', function () {
        var userId = $(this).data('user-id');
        if (confirm('Действительно удалить пользователя')) {
            $.ajax({
                url: '/api/user/index',
                type: "DELETE",
                data: {
                    userId: userId
                },
                success: function (success) {
                    if (success) {
                        $('tr[data-user-id="' + userId + '"]').remove();
                    }
                }
            });
        }
    });
};

var profileAccessWidget = new ProfileAccessWidget();
profileAccessWidget.init();