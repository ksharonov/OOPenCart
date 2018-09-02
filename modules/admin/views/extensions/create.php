<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Extension */

$this->title = 'Добавление расширения';
$this->params['breadcrumbs'][] = ['label' => 'Extensions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extension-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
