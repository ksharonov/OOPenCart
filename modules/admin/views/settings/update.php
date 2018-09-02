<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Setting */

$this->title = 'Обновление настройки';
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="setting-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
