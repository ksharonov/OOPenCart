<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Order */

$this->title = "Заказ №{$model->id} от {$model->dtc}";
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="order-update">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider
    ]) ?>

</div>
