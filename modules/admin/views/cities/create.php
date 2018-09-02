<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\CityOnSite */

$this->title = 'Create Cities On Site';
$this->params['breadcrumbs'][] = ['label' => 'Cities On Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-on-site-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
