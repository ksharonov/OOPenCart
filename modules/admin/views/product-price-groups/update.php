<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductPriceGroup */

$this->title = 'Update Product Price Group: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Product Price Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-price-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
