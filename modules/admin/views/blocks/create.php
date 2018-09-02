<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Block */

$this->title = 'Добавление блока';
$this->params['breadcrumbs'][] = ['label' => 'Блоки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
