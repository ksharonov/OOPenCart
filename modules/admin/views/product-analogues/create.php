<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\ProductAnalogue */

$this->title = 'Добавить аналог товара';
$this->params['breadcrumbs'][] = ['label' => 'Аналоги товара', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-analogue-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
