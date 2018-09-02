<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\ProductCategory */

$this->title = 'Добавление категории';
$this->params['breadcrumbs'][] = ['label' => 'Категории продуктов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
