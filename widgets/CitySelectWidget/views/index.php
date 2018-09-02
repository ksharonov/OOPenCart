<?php

use yii\widgets\Pjax;
use app\system\base\widgets\ActiveForm;
use yii\helpers\Html;

//    Pjax::begin();
    $form = ActiveForm::begin([
        'options' => [
//            'data-pjax' => '',
            'id' => 'w2'
        ]
    ]);

//    if ($model->citySelected !== "null" && $model->citySelected !== null) {
//        echo $form->field($model, 'citySelected')->hiddenInput(['value' => "null"])->label(false);
//        echo Html::a($model->city->title, " ",
//            [
//                'class' => "header__town-select",
//                'id' => "citysession-citysubmit",
//                'data-pjax' => "1"
//            ]
//        );
//    } else {
//        echo $form->field($model, 'citySelected')->dropDownList($cities, ['prompt' => 'Выберите город', 'class' => 'sort__select header__town-select'])
//            ->label(false);
//    }

echo $form->field($model, 'citySelected')->dropDownList($cities, ['prompt' => 'Выберите город', 'class' => 'sort__select header__town-select'])
    ->label(false);

    ActiveForm::end();
//    Pjax::end();

?>






