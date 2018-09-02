<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Manufacturer */

$this->title = 'Обновление производителя';
$this->params['breadcrumbs'][] = ['label' => 'Производители', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="manufacturer-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
