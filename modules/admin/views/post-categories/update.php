<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\db\PostCategory */

$this->title = 'Обновление категории';
$this->params['breadcrumbs'][] = ['label' => 'Категории постов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновленме';
?>
<div class="post-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
