<?php

use yii\widgets\Pjax;
use kartik\grid\GridView;
use yii\helpers\Html;

?>

<div class="client-users-widget">
    <br>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
        Добавить пользователя
    </button>
    <br><br>
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Пользователи</h4>
                </div>
                <div class="modal-body">
                    <?php Pjax::begin(['timeout' => 10000]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $userProvider,
                        'filterModel' => $userSearchModel,
                        'summary' => false,
                        'columns' => [
                            'id',
                            'username',
                            'fio',
                            'phone',
                            [
                                'label' => 'Действие',
                                'format' => 'raw',
                                'value' => function ($model) use ($clientModel) {
                                    return Html::tag('a', 'Добавить', [
                                        'href' => '#',
                                        'class' => 'unit-add btn btn-primary btn-sm',
                                        'onclick' => "adminClientUser.add($clientModel->id, $model->id)"
                                    ]);
                                }
                            ]
                        ],
                    ]); ?>

                    <?php Pjax::end(); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <?php Pjax::begin(['timeout' => 10000, 'id' => 'users']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'product-file'],
        'rowOptions' => ['class' => 'product-file__item'],
        'columns' => [
            'id',
            'fio',
            'phone',
            [
                'label' => 'Действие',
                'format' => 'raw',
                'value' => function ($model) use ($clientModel) {
                    return
                        Html::tag('a', 'Удалить', [
                            'href' => '#',
                            'class' => 'btn btn-danger btn-sm',
                            'onclick' => "adminClientUser.remove($clientModel->id, $model->id)"
                        ]);
                }
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
