var adminClientUser = new AdminClientUserClass();

/** Класс для работы с пользователями клиента */
function AdminClientUserClass() {
    /*
     * Инициализация
     */
    this.init = function () {

    };

    /*
     * Добавление нового пользователя к клиенту
     */
    this.add = function (clientId, userId) {
        var data = {
            clientId: clientId,
            userId: userId
        };
        $.post(
            '/admin/api/client/client-users/set',
            data,
            function (success) {
                adminClientUser.success();
            }
        );
    };

    /*
     * Удаление пользователя из клиента
     */
    this.remove = function (clientId, userId) {
        var data = {
            clientId: clientId,
            userId: userId
        };
        $.post(
            '/admin/api/client/client-users/delete',
            data,
            function (success) {
                adminClientUser.success();
            }
        );
    };

    /*
     * Обновление
     */
    this.success = function () {
        $.pjax.reload({container: '#users'});
        $('#addUserModal').modal('hide');
    };
}