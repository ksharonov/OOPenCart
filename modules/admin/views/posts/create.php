<?php

use yii\helpers\Html;
use app\models\db\Post;

///* @var $this yii\web\View */
/* @var $model app\models\db\Post */

$this->title = 'Добавить пост';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <?php
    if ($model->type == Post::TYPE_REVIEWS || $model->type == Post::TYPE_NEWS) {
        echo $this->render('_form_news-reviews', [
            'model' => $model,
        ]);
    } elseif ($model->type == Post::TYPE_VACANCY) {
        echo $this->render('_form_vacancy', [
            'model' => $model,
        ]);
    }

    ?>

</div>
