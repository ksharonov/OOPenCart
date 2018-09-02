<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\Seo */

$this->title = 'Обновление SEO: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="seo-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
