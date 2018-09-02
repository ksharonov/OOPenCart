<?php
use app\models\db\Client;
use app\models\db\User;
use app\models\db\UserToClient;

/* @var User $user */
/* @var Client $client */

/* @var UserToClient[] $usersClient */
/* @var \app\models\db\Address[] $addresses */

$usersClient = $client->userToClient;

foreach ($usersClient as $userClient) {
//    dump([
//        'user' => $userClient->user,
//        'position' => $userClient->position
//    ]);
}
?>
<?php
echo $this->render('address_popup', []);
?>
<div class="access">
    <div class="row">
        <h2 class="profile__title profile__title_mini col-lg-48">Список адресов</h2>
        <div class="col-lg-48">
            <div class="tbl-wrap">
                <table class="tbl access__table address__table">
                    <thead class="tbl__head">
                    <tr>
                        <th class="tbl__th">Страна</th>
                        <th class="tbl__th">Город</th>
                        <th class="tbl__th">Индекс</th>
                        <th class="tbl__th">Адрес</th>
                        <th class="tbl__th">Действия</th>
                    </tr>
                    </thead>
                    <tbody class="tbl__body">
                    <?php foreach ($addresses as $address) { ?>
                        <tr data-address-id="<?= $address->id ?>">
                            <td class="tbl__td" data-name="country">
                                <?= $address->country->title ?>
                            </td>
                            <td class="tbl__td" data-name="city">
                                <?= $address->city->title ?>
                            </td>
                            <td class="tbl__td" data-name="postcode">
                                <?= $address->postcode ?>
                            </td>
                            <td class="tbl__td" data-name="address">
                                <?= $address->address ?>
                            </td>
                            <td class="tbl__td">
                                <a href="#" class="tbl__action tbl__action_edit _client_address_edit"
                                   data-address-id="<?= $address->id ?>"
                                   data-m-toggle="modal" data-m-target="#createAddressModal"
                                   title="Редактировать"></a>
                                <a href="#" class="tbl__action tbl__action_delete _client_address_delete"
                                   data-address-id="<?= $address->id ?>"
                                   title="Удалить"></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <a href="#" class="access__add" data-m-toggle="modal" data-m-target="#createAddressModal">Добавить
                адрес</a>
        </div>

    </div>
</div>
