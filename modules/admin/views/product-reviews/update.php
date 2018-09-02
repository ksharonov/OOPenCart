<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductReview */

$this->title = 'Обновление отзыва: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Product Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-review-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
