<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Template */

$this->title = 'Обновление шаблона: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="template-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
