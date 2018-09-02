<?php

use kartik\grid\GridView;


//$connect = new \app\modules\lexema\models\LexemaConnect();
//$import = new \app\modules\lexema\models\LexemaImport($connect);
//
//$data = $import->getDocumentDetails($model['vcode']);
//
//$dataProvider = new \yii\data\ArrayDataProvider([
//    'allModels' => $data,
//]);


echo GridView::widget([
    'dataProvider' => $model,
    'layout' => "{items}",
    'columns' => [
        [
            'attribute' => 'materialGlobalId',
            'label' => 'Наименование товара',
        ],
        [
            'attribute' => 'count',
            'label' => 'Кол-во',
        ],
        [
            'attribute' => 'ozena2',
            'label' => 'Цена',
        ],
        [
            'attribute' => 'sumBndsRSH',
            'label' => 'Сумма без НДС',
        ],
        [
            'attribute' => 'sumndsRSH',
            'label' => 'Сумма НДС',
        ],
        [
            'attribute' => 'sumSndsRSH',
            'label' => 'Сумма c НДС',
        ],
    ]
]);
?>