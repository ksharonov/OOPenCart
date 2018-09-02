<?php
use yii\bootstrap\Html;

?>

<?= Html::activeHiddenInput($model, $attribute, ['class' => 'hidden-param']); ?>

    <div class="row">
        <div class="col-md-12">
            <?= Html::label($label, null, ['class' => 'control-label']); ?>
        </div>
    </div>


    <div class="dynamic-params">
        <?php foreach ($data as $key => $value) { ?>
            <div class="row dynamic-params__item" data-param>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= Html::input('text', 'ParamsKey', $key, [
                            'class' => 'form-control dynamic-params__item-key',
                            'placeholder' => 'Параметр',
                            'disabled' => !$extendable
                        ]); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= Html::input('text', 'ParamsValue', $value, [
                            'class' => 'form-control dynamic-params__item-value',
                            'placeholder' => 'Значение'
                        ]); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

<?php if ($extendable) { ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::button('Добавить', [
                    'class' => 'btn btn-flat btn-sm btn-info',
                    'onclick' => 'dynamicParam.add()'
                ]); ?>
            </div>
        </div>
    </div>

<?php } ?>