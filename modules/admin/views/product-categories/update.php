<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductCategory */

$this->title = 'Обновление категории продукта';
$this->params['breadcrumbs'][] = ['label' => 'Категории продуктов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="product-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
