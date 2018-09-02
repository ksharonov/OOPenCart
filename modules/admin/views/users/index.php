<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\db\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-flat btn-sm btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'filter' => User::$statuses,
                'value' => function ($model) {
                    return User::$statuses[$model->status] ?? null;
                }
            ],
//            'password',
//            'dtcreate',
            // 'access_token',
            // 'authKey',
            // 'change_password_key',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
