<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\OrderStatus */

$this->title = 'Создание статуса';
$this->params['breadcrumbs'][] = ['label' => 'Order Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-status-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
