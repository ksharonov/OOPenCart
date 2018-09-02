<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\db\Client;
use app\modules\admin\widgets\client\ClientUsersWidget\ClientUsersWidget;
use app\modules\admin\widgets\common\DynamicParamsWidget\DynamicParamsWidget;

/* @var $this yii\web\View */
/* @var $model app\models\db\Client */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="client-form">

    <div class="" data-example-id="togglable-tabs">
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#main" id="main-tab" role="tab" data-toggle="tab"
                   aria-controls="main" aria-expanded="true">Клиент</a>
            </li>
            <li role="presentation" class="">
                <a href="#user" role="tab" id="user-tab" data-toggle="tab"
                   aria-controls="user" aria-expanded="false">Пользователи</a>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade active in" role="tabpanel" id="main" aria-labelledby="main-tab">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'type')->dropDownList(Client::$types) ?>

            <?= $form->field($model, 'status')->dropDownList(Client::$statuses) ?>

            <?= DynamicParamsWidget::widget([
                'model' => $model,
                'attribute' => 'params',
                'extendable' => false
            ]); ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="tab-pane" role="tabpanel" id="user" aria-labelledby="user-tab">
            <?= ClientUsersWidget::widget([
                'model' => $model
            ]); ?>
        </div>
    </div>

</div>
