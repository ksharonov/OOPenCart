<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductFilterFast */

$this->title = 'Редактирование быстрого фильтра';
$this->params['breadcrumbs'][] = ['label' => 'Быстрые фильтры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title ?? 'Новый', 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-filter-fast-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
