<?php
use kartik\grid\GridView;
?>
<div class="Admin-default-index">
    <h1><?= "Документы клиента \"" . $title . "\"" ?></h1>
    <div class = "center">
        <?php
        $gridColumns = [
            [
                'class' => 'kartik\grid\SerialColumn',
            ],
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'enableRowClick' => true,
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
//                'detail' => function ($model, $key, $index, $column) {
//                    return Yii::$app->controller->renderPartial('_expand-details', ['model' => $model]);
//                },
                'detailUrl' => 'details',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true
            ],
            [
                'attribute' => 'Nomer',
                'label' => 'Номер документа',
            ],
            [
                'attribute' => 'vcode',
                'label' => 'Код',
            ],
            [
                'attribute' => 'tdoc',
                'label' => 'tdoc',
            ],
            [
                'attribute' => 'docName',
                'label' => 'Имя документа',
            ],
        ];

        echo \kartik\grid\GridView::widget([
            'dataProvider' => $provider,
            'columns' => $gridColumns,
            'pjax' => true,
        ]);
        ?>

    </div>
</div>

