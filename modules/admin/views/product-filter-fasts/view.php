<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\db\ProductFilterFast */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Product Filter Fasts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-filter-fast-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title:ntext',
            'name',
            'categoryId',
            'expanded',
        ],
    ]) ?>

</div>
