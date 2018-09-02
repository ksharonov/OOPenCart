<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Unit */

$this->title = 'Обновление ЕИ';
$this->params['breadcrumbs'][] = ['label' => 'Единицы измерения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="unit-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
