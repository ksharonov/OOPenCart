<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Setting */

$this->title = 'Создание настройки';
$this->params['breadcrumbs'][] = ['label' => 'Настройки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
