<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Manufacturer */

$this->title = 'Добавление производителя';
$this->params['breadcrumbs'][] = ['label' => 'Производители', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manufacturer-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
