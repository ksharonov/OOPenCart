<?php
use app\models\db\Client;
use app\models\db\User;
use app\models\db\UserToClient;

/* @var User $user */
/* @var Client $client */

/* @var UserToClient[] $usersClient */

$usersClient = $client->userToClient;

foreach ($usersClient as $userClient) {
//    dump([
//        'user' => $userClient->user,
//        'position' => $userClient->position
//    ]);
}
?>
<?php
echo $this->render('access_popup', [
]);
?>
<div class="access">
    <div class="row">
        <h2 class="profile__title profile__title_mini col-lg-48">Список пользователей</h2>
        <div class="col-lg-48">
            <div class="tbl-wrap">
                <table class="tbl access__table">
                    <thead class="tbl__head">
                    <tr>
                        <th class="tbl__th">Имя пользователя</th>
                        <th class="tbl__th">ФИО</th>
                        <th class="tbl__th">Телефон</th>
                        <th class="tbl__th">Почта</th>
                        <th class="tbl__th">Уровень доступа</th>
                        <th class="tbl__th">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="tbl__body">
                    <?php foreach ($users as $userClient) { ?>
                        <tr data-user-id="<?= $userClient->userId ?>">
                            <td class="tbl__td" data-name="username"><?= $userClient->user->username ?></td>
                            <td class="tbl__td" data-name="fio"><?= $userClient->user->fio ?></td>
                            <td class="tbl__td" data-name="phone"><?= $userClient->user->phone ?></td>
                            <td class="tbl__td" data-name="email"><?= $userClient->user->email ?></td>
                            <td class="tbl__td"><?= UserToClient::$positions[$userClient->position] ?></td>
                            <td class="tbl__td">
                                <!--                                <a href="#" class="tbl__action tbl__action_edit"-->
                                <!--                                   data-user-id="-->
                                <? //= $userClient->userId ?><!--"-->
                                <!--                                   title="Редактировать"></a>-->
                                <a href="#" class="tbl__action tbl__action_edit _client_user_edit"
                                   data-m-toggle="modal"
                                   data-user-id="<?= $userClient->userId ?>"
                                   data-m-target="#createAccessModal"
                                   title="Редактировать"></a>
                                <a href="#" class="tbl__action tbl__action_delete _client_user_delete"
                                   data-user-id="<?= $userClient->userId ?>"
                                   title="Удалить"></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <a href="#" class="access__add" data-m-toggle="modal" data-m-target="#createAccessModal">Добавить
                пользователя</a>
        </div>

    </div>
</div>
