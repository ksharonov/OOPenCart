<?php
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use app\models\db\Order;
use app\models\search\OrderSearchProfile;
use app\widgets\PaginationWidget\PaginationWidget;
use app\helpers\NumberHelper;

/* @var OrderSearchProfile $searchModel */
/* @var Order $order */
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var \app\components\ClientComponent $client */
/* @var \app\models\db\Extension[] $payments */
$client = Yii::$app->client;
?>


<?php Pjax::begin(['timeout' => 100000, 'options' => ['class' => 'orders']]) ?>
<?php
if ($client->isEntity()) {
    echo $this->render('single_entity', [
        'order' => $order,
        'searchModel' => $searchModel,
        'payments' => $payments
    ]);
} elseif ($client->isIndividual()) {
    echo $this->render('single_individual', [
        'order' => $order,
        'searchModel' => $searchModel,
        'payments' => $payments
    ]);
}
?>

    <div class="row">
        <h2 class="profile__title profile__ti   tle_mini col-lg-48">Заказы</h2>

        <?php echo $this->render('_search', ['model' => $searchModel]); ?>

        <div class="col-lg-48">
            <div class="orders__table">
                <div class="tbl-wrap">
                    <table class="tbl tbl_fullwidth">
                        <thead class="tbl__head">
                        <tr>
                            <th class="tbl__th">
<!--                                <div class="check">-->
<!--                                    <input id="check-all" type="checkbox" hidden class="check__input">-->
<!--                                    <label class="check__label" for="check-all"></label>-->
<!--                                </div>-->
                            </th>
                            <th class="tbl__th">Номер заказа</th>
                            <th class="tbl__th">Дата</th>
                            <th class="tbl__th">Пользователь</th>
                            <th class="tbl__th">Статус заказа</th>
                            <th class="tbl__th">Доставка</th>
                            <th class="tbl__th">Сумма без НДС</th>
                            <th class="tbl__th">Сумма с НДС</th>
                        </tr>
                        </thead>
                        <tbody class="tbl__body">
                        <?php foreach ($dataProvider->models as $model) { ?>
                            <?php /* @var Order $model */ ?>
                            <tr>
                                <td>
                                    <div class="check">
                                        <input id="check-<?= $model->id; ?>" type="checkbox" hidden
                                               data-order-id="<?= $model->id; ?>"
                                               class="check__input _profile_order_list_item">
                                        <label class="check__label" for="check-<?= $model->id; ?>"></label>
                                    </div>
                                </td>
                                <td class="tbl__td">
                                    <?= Html::a($model->orderNumber ?? $model->id, "?id={$model->id}&OrderSearchProfile[search]={$searchModel->search}", [
                                        'data-id' => $model->id,
                                        'class' => '_order_open'
                                    ]); ?>
                                </td>
                                <td class="tbl__td"><?= $model->dt; ?></td>
                                <td class="tbl__td"><?= $model->user->fio ?? $model->client->title ?? null; ?></td>
                                <td class="tbl__td"><?= $model->lastStatus->title ?? null ?></td>
                                <td class="tbl__td"><?= $model->dtDelivery; ?></td>
                                <td class="tbl__td"><?= NumberHelper::asMoney($model->sumWVat); ?> <span
                                            class="rouble">₽</span></td>
                                <td class="tbl__td"><?= NumberHelper::asMoney($model->sum); ?> <span
                                            class="rouble">₽</span></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?= PaginationWidget::widget([
            'id' => 'orders-list',
            'pagination' => $dataProvider->pagination
        ]);
        ?>
    </div>
<?php Pjax::end(); ?>