<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\ProductOption */

$this->title = 'Создать новую опцию';
$this->params['breadcrumbs'][] = ['label' => 'Опции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-option-create">
    <div class="col-sm-8">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>
