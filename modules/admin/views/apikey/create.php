<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Apikey */

$this->title = 'Create Apikey';
$this->params['breadcrumbs'][] = ['label' => 'Apikeys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apikey-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
