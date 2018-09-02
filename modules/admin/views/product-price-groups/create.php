<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\ProductPriceGroup */

$this->title = 'Create Product Price Group';
$this->params['breadcrumbs'][] = ['label' => 'Product Price Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-price-group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
