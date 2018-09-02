<?php
use app\models\db\User;
use app\models\db\Client;
use yii\widgets\Pjax;

/* @var yii\web\View $this */
/* @var User $user */
/* @var Client $client */
$isManager = $client->param->manager ?? false;
?>
<?php Pjax::begin(['timeout' => 10000, 'options' => ['id' => 'user-edit-pjax']]) ?>
<?= $this->render('edit', [
    'model' => $user
]) ?>
<?php Pjax::end() ?>
<?php Pjax::begin(['timeout' => 10000, 'options' => ['class' => 'entity', 'id' => 'user-view-pjax']]) ?>
    <div class="row">
        <div class="col-lg-16 col-md-20">
            <div class="entity__profile">
                <div class="entity__name"><?= $user->fio ?? '&nbsp;' ?></div>
                <p class="entity__contact entity__contact_email">
                    <?= $user->email ?? '&nbsp;' ?>
                </p>
                <p class="entity__contact entity__contact_phone">
                    <?= $user->phone ?? '&nbsp;' ?>
                </p>
                <a href="#" class="entity__edit" data-m-toggle="modal" data-m-target="#userEdit">Редактировать
                    профиль</a>
            </div>
        </div>
        <?php if (Yii::$app->user->identity->lexemaCard || Yii::$app->user->identity->lexemaDiscountCard) { ?>
            <div class="col-lg-16 col-md-20">
                <div class="entity__block">
                    <div class="entity__values entity__values_single justify">
                        <div class="justify__item">
                            <div class="entity__label">Номер карты</div>
                            <div class="entity__value"><?= $user->lexemaDiscountCard->number ?? $user->lexemaCard->number ?? 'Отсутствует' ?></div>
                        </div>
                        <div class="justify__item">
                            <div class="entity__label">Баллы</div>
                            <div class="entity__value"><?= $user->lexemaCard->bonuses ?? '0' ?></div>
                        </div>
                        <div class="justify__item">
                            <div class="entity__label">Скидка</div>
                            <div class="entity__value"><?= $user->lexemaDiscountCard->discountValue ?? '0' ?>%
                            </div>
                        </div>
                    </div>
                    <div class="justify" hidden>
                        <div class="justify__item">
                            <div class="entity__label">Статус покупателя</div>
                            <div class="entity__value">Супер VIP</div>
                        </div>
                        <div class="justify__item">
                            <div class="entity__label">
                                <a href="#" class="entity__contract">
                                    Действующий договор
                                </a>
                            </div>
                            <div class="entity__value">до 12.09.18</div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($isManager) { ?>
            <div class="col-lg-16 col-md-20">
                <div class="manager manager_single">
                    <h2 class="manager__title">Ваш менеджер</h2>
                    <p class="manager__contact manager__contact_person">
                        <?= $client->param->manager ?>
                    </p>
                    <p class="manager__contact manager__contact_email">
                        <?= $client->param->managerEmail ?>
                    </p>
                    <p class="manager__contact manager__contact_phone">
                        <?= $client->param->managerPhone ?>
                    </p>
                </div>
            </div>
        <?php } ?>
    </div>
<?php Pjax::end() ?>