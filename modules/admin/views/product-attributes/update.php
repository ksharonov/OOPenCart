<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductAttribute */

$this->title = 'Обновление атрибута' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="product-attribute-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
