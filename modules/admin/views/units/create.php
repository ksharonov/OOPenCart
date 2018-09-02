<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Unit */

$this->title = 'Добавление ЕИ';
$this->params['breadcrumbs'][] = ['label' => 'Единицы измерения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
