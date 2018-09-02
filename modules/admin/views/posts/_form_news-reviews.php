<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use dosamigos\tinymce\TinyMce;
use yii\helpers\ArrayHelper;
use \app\models\db\PostCategory;
use mihaildev\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
use unclead\multipleinput\MultipleInput;
use app\modules\admin\widgets\product\ProductFileWidget\ProductFileWidget;

/* @var $this yii\web\View */
/* @var $model app\models\db\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'type')->dropDownList($model::$types, [
                'disabled' => true
            ]) ?>
        </div>
        <div class="col-md-6">
            <?php $form->field($model, 'thumbnail')->widget(InputFile::className(), [
                'controller'    => 'admin/elfinder',
                'path'          => 'image',
                'filter'        => 'image',
                'template'      => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                'options'       => ['class' => 'form-control'],
                'buttonOptions' => ['class' => 'btn btn-default'],
                'multiple'      => false
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'dtCreate')->widget(DateTimePicker::classname(), [
                'class'         => 'form-control',
                'convertFormat' => true,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format'    => 'yyyy-M-dd H:i:s'
                ]
            ]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'status')->dropDownList($model::$postStatuses) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'categoryId')->dropDownList(
                ArrayHelper::map(PostCategory::find()->select('id, title')->all(), 'id', 'title'), [
                'prompt' => '- Выбрать категорию -',
                'value'  => $model->categoryId
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if ($model::$defaultParams) { ?>
                <?= $form->field($model, 'params')->widget(MultipleInput::className(), [
                    'max'            => 2,
                    'min'            => 2,
                    'value'          => $model->param->asArray ?? $model::$defaultParams,
                    'allowEmptyList' => true,
                    'columns'        => [
                        [
                            'name'         => 'title',
                            'type'         => \unclead\multipleinput\components\BaseColumn::TYPE_STATIC,
                            'title'        => 'Название поля',
                            'defaultValue' => null,
                            'options'      => [
//                            'disabled' => true,
                            ]
                        ],
                        [
                            'name'         => 'title',
                            'type'         => \unclead\multipleinput\components\BaseColumn::TYPE_HIDDEN_INPUT,
                            'title'        => 'Название поля',
                            'defaultValue' => null,
                            'options'      => [
//                            'disabled' => true,
                            ]
                        ],
                        [
                            'name'         => 'key',
                            'type'         => \unclead\multipleinput\components\BaseColumn::TYPE_HIDDEN_INPUT,
                            'title'        => 'Ключ поля',
                            'defaultValue' => null,
                            'options'      => [
//                            'disabled' => true
                            ]
                        ],
                        [
                            'name'         => 'value',
                            'title'        => 'Значение поля',
                            'enableError'  => true,
                            'defaultValue' => null,
                        ]
                    ]
                ])
                         ->label('Дополнительные поля');
                ?>
            <?php } ?>
        </div>
    </div>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'data-from-translit' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['data-to-translit' => true]) ?>

    <?= $form->field($model, 'content')->widget(TinyMce::className(), [
        'options'       => ['rows' => 20],
        'language'      => 'ru',
        'clientOptions' => [
            'invalid_styles' => [
                'table' => 'height width',
                'td'    => 'height width',
                'tr'    => 'height width',
                'th'    => 'height width',
            ],
            'valid_classes'  => [
                '*' => 'class',
            ],
            'plugins'        => [
                "advlist autolink lists link charmap print preview anchor code",
                "searchreplace visualblocks fullscreen",
                "insertdatetime media table image contextmenu paste"
            ],
            'toolbar'        => "undo redo | styleselect | bold italic underline | 
                alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image blockquote"
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php if (!$model->isNewRecord) {
        echo ProductFileWidget::widget([
            'model'     => $model,
            'withImage' => true
        ]);
    } ?>


</div>