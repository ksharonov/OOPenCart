<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductFilter */

$this->title = 'Обновить фильтр';
$this->params['breadcrumbs'][] = ['label' => 'Фильтры товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="product-filter-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
