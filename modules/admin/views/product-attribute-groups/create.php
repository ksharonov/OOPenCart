<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\ProductAttributeGroup */

$this->title = 'Добавить группу атрибутов';
$this->params['breadcrumbs'][] = ['label' => 'Группы атрибутов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
