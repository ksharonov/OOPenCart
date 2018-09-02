<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Users */

$this->title = 'Обновление пользователей';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="users-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
