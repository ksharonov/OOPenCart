<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Extension */

$this->title = 'Обновление расширения: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Extensions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="extension-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
