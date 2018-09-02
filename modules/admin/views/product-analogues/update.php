<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductAnalogue */

$this->title = 'Обновление аналога товара';
$this->params['breadcrumbs'][] = ['label' => 'Аналоги продуктов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="product-analogue-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
