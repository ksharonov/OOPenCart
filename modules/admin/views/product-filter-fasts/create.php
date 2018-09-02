<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\ProductFilterFast */

$this->title = 'Редактирование быстрого фильтра';
$this->params['breadcrumbs'][] = ['label' => 'Быстрые фильтры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title ?? 'Новый';
?>
<div class="product-filter-fast-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
