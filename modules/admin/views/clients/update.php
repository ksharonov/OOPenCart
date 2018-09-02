<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Client */

$this->title = 'Обновление клиента';
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="client-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
