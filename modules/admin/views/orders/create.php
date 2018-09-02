<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Order */

$this->title = 'Добавить заказ';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider
    ]) ?>

</div>
