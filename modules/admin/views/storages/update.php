<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Storage */

$this->title = 'Обновление склада: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Склады', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="storage-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
