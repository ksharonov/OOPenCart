<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\db\Seo */

$this->title = 'Добавить SEO';
$this->params['breadcrumbs'][] = ['label' => 'Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
