<?php
use app\models\db\User;
use app\models\db\Client;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;

/* @var yii\web\View $this */
/* @var User $user */
/* @var Client $client */


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
                <div class="entity__name"><?= $user->fio ?></div>
                <p class="entity__contact entity__contact_email">
                    <?= $user->email ?? '&nbsp;' ?>
                </p>
                <p class="entity__contact entity__contact_phone">
                    <?= $user->phone ?? '&nbsp;' ?>
                </p>
                <a href="#" class="entity__edit" data-m-toggle="modal" data-m-target="#userEdit">
                    Редактировать профиль</a>
                <div class="entity__values justify"></div>
                <div class="justify">
                    <div class="justify__item">
                        <div class="entity__label">Статус покупателя</div>
                        <!--                        <div class="entity__value">Супер VIP</div>-->
                    </div>
                    <div class="justify__item">
                        <div class="entity__label">
                            <a href="#" class="entity__contract">
                                Действующий договор <?= $client->contract->number ?? null ?>
                            </a>
                        </div>
                        <!--                        <div class="entity__value">до -->
                        <? //= $client->contract->dateEnd ?? null ?><!-- </div>-->
                    </div>
                </div>
            </div>
            <?php if (isset($client->param->manager)) { ?>
                <div class="manager">
                    <h2 class="manager__title">Ваш менеджер</h2>
                    <p class="manager__contact manager__contact_person">
                        <?= $client->param->manager ?>
                    </p>
                    <p class="manager__contact manager__contact_email">
                        <?= $client->param->managerEmail?>
                    </p>
                    <p class="manager__contact manager__contact_phone">
                        <?= $client->param->managerPhone ?>
                    </p>
                </div>
            <?php } ?>
        </div>
        <div class="col-lg-31 col-lg-push-1 col-md-28">
            <h2 class="profile__subtitle">Сведения о юридическом лице</h2>
            <div class="profile__info">
                <div class="infoblock">
                    <div class="infoblock__label">Наименование
                        <?= \app\models\db\Block::getDropdown('DROPDOWN.PROFILE.CLIENT.TITLE') ?>
                    </div>

                    <div class="infoblock__value"><?= $client->title ?></div>
                </div>
                <div class="infoblock">
                    <div class="infoblock__label">Телефон
                        <?= \app\models\db\Block::getDropdown('DROPDOWN.PROFILE.CLIENT.PHONE') ?>
                    </div>
                    <div class="infoblock__value"><?= $client->phone; ?></div>
                </div>
                <div class="infoblock">
                    <div class="infoblock__label">Эл. почта
                        <?= \app\models\db\Block::getDropdown('DROPDOWN.PROFILE.CLIENT.EMAIL') ?>
                    </div>
                    <div class="infoblock__value"><?= $client->email ?? $user->email ?></div>
                </div>
                <div class="infoblock">
                    <div class="infoblock__label">ИНН
                        <?= \app\models\db\Block::getDropdown('DROPDOWN.PROFILE.CLIENT.INN') ?>
                    </div>
                    <div class="infoblock__value"><?php echo $client->param->inn ?></div>
                </div>
                <div class="infoblock">
                    <div class="infoblock__label">КПП
                        <?= \app\models\db\Block::getDropdown('DROPDOWN.PROFILE.CLIENT.KPP') ?>
                    </div>
                    <div class="infoblock__value"><?= $client->param->kpp ?></div>
                </div>
                <div class="infoblock">
                    <div class="infoblock__label">Юр. адрес
                        <?= \app\models\db\Block::getDropdown('DROPDOWN.PROFILE.CLIENT.ADDRESS') ?>
                    </div>
                    <div class="infoblock__value">
                        <?= $client->param->lawAddress ?>
                    </div>
                </div>
            </div>
            <h2 class="profile__subtitle">Сведения о взаимозачетах</h2>
            <div class="profile__info">
                <div class="infoblock">
                    <div class="infoblock__label">
                        Сумма баланса
                        <?= \app\models\db\Block::getDropdown('DROPDOWN.PROFILE.CLIENT.BALANCE') ?>
                    </div>
                    <div class="infoblock__value">
                        <?= $client->param->balance ?><span class="rouble">₽</span>
                    </div>
                </div>
                <div class="infoblock">
                    <div class="infoblock__label">
                        Сумма заказов
                    </div>
                    <div class="infoblock__value">
                        <?= $client->param->paymentSumm ?>
                        <span class="rouble">₽</span></div>
                </div>
                <div class="infoblock">
                    <div class="infoblock__label">
                        Сумма и дата последнего платежа
                    </div>
                    <div class="infoblock__value">
                        <?= $client->param->lastPaymentSumm ?>
                        <span class="rouble">₽</span> <?= $client->param->lastPaymentDate ?>
                    </div>
                </div>
                <?php if (false) { ?>
                    <div class="infoblock">
                        <div class="infoblock__label">Отсрочка платежа</div>
                        <div class="infoblock__value"><?= $client->param->postponement ?></div>
                    </div>
                    <div class="infoblock">
                        <div class="infoblock__label">Сумма лимита отсрочки</div>
                        <div class="infoblock__value"><?= $client->param->creditLimit ?><span
                                    class="rouble">₽</span></div>
                    </div>
                <?php } ?>
            </div>
            <div class="profile__subtitle">Акт сверки</div>
            <div class="profile__info">

            </div>
            <?= \app\modules\profile\widgets\ReconciliationWidget\ReconciliationWidget::widget() ?>
        </div>
    </div>
<?php Pjax::end() ?>