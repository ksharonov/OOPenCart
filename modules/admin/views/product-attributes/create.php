<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\ProductAttribute */

$this->title = 'Добавление атрибута';
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты продуктов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-attribute-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
