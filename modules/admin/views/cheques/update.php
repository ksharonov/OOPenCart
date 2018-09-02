<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Cheque */

$this->title = 'Update Cheque: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cheques', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cheque-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
