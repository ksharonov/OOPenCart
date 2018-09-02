<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductAttributeGroup */

$this->title = 'Обновить группу атрибутов: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Группы атрибутов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="product-attribute-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
