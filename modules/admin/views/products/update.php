<?php

use yii\helpers\Html;
//use app\assets\AdminAsset;

/* @var $this yii\web\View */
/* @var $model app\models\db\Product */
//AdminAsset::register($this);

$this->title = 'Обновление товара';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="product-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
