<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Discount */

$this->title = 'Обновить скидку: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="discount-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
