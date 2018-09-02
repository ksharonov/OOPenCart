<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Template */

$this->title = 'Добавить шаблон';
$this->params['breadcrumbs'][] = ['label' => 'Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
