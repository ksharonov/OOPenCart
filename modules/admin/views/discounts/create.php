<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Discount */

$this->title = 'Добавить скидку';
$this->params['breadcrumbs'][] = ['label' => 'Discounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discount-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
