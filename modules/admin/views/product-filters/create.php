<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\ProductFilter */

$this->title = 'Добавить фильтр товара';
$this->params['breadcrumbs'][] = ['label' => 'Фильтры товаров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-filter-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
