<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Storage */

$this->title = 'Добавить склад';
$this->params['breadcrumbs'][] = ['label' => 'Склады', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="storage-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
